<?php

class CommentsController extends BaseController
{
	public function addComment($id)
	{
		if(Request::ajax()) {
			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);

			$ip = Ip::firstOrCreate(['ip' => Request::ip()]);
			$page = Page::findOrFail($id);

			$userData = [
				'is_answer' => (Page::TYPE_QUESTION == $page->type && 0 == $formFields['parent_id']) ? 1 : 0,
				'page_id' => $id,
				'parent_id' => $formFields['parent_id'],
				'user_id' => Auth::check() ? Auth::user()->id : null,
				'user_name' => isset($formFields['user_name']) ? $formFields['user_name'] : null,
				'ip_id' => $ip->id,
				'user_email' => isset($formFields['user_email']) ? $formFields['user_email'] : null,
				'comment' => StringHelper::nofollowLinks($formFields['comment']),
				'is_published' => Auth::check() ? 1 : 0,
				'g-recaptcha-response' => Auth::check() ? '' : $formFields['g-recaptcha-response'],
			];

			if(isset($formFields['user_name'])) {
				Session::set('user.user_name', $formFields['user_name']);
			}
			if(isset($formFields['user_email'])) {
				Session::set('user.user_email', $formFields['user_email']);
			}

			// проверка капчи
//			$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=YOUR-SECRET-KEY-HERE&response=" . $captcha);
//			if ($response . success == false) {
//				echo 'SPAM';
//				http_response_code(401); // It's SPAM! RETURN SOME KIND OF ERROR
//			} else {
//				// Everything is ok and you can proceed by executing your login, signup, update etc scripts
//			}

			$validator = Validator::make($userData, Comment::$rules);

			if ($validator->fails())
				return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray(),
				));
			else {
				// save to DB user details
				if ($comment = Comment::create($userData)) {
					// adding points for comment
					if($comment->user) {
						if($comment->is_answer) {
							$comment->user->addPoints(User::POINTS_FOR_ANSWER);
							$comment->user->setNotification(Notification::TYPE_POINTS_FOR_ANSWER_ADDED);
							$comment->page->user->setNotification(Notification::TYPE_NEW_ANSWER);
						} else {
							$comment->user->addPoints(User::POINTS_FOR_COMMENT);
							$comment->user->setNotification(Notification::TYPE_POINTS_FOR_COMMENT_ADDED);
							$comment->page->user->setNotification(Notification::TYPE_NEW_COMMENT);
						}
					}
					// return success message
					if(Auth::check()) {
						$commentHtml = (0 == $comment->parent_id)
							? (string) View::make('widgets.comment.comment1Level', compact('comment'))->with('page', $comment->page)->with('isBannedIp', Ip::isBanned())->render()
							: (string) View::make('widgets.comment.comment2Level')->with('page', $comment->page)->with('isBannedIp', Ip::isBanned())->with('commentLevel2', $comment)->render();
					} else {
						$commentHtml = '';
					}
					return Response::json(array(
						'success' => true,
						'parent_id' => $comment->parent_id,
						'comment_id' => $comment->id,
						'commentHtml' => $commentHtml,
						'countComments' => (Page::TYPE_QUESTION == $page->type)
							? count($page->publishedAnswers) - count($page->bestComments)
							: count($page->publishedComments),
					));
				}
			}
		}
	}

	/**
	 * Голосование за комментарии
	 *
	 * @param $commentId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function vote($commentId)
	{
		if(Request::ajax()) {

			if(Auth::check()) {
				if(Auth::user()->is_banned) {
					return Response::json(array(
						'success' => false,
						'message' => 'Вы забанены администратором сайта и не можете голосовать.'
					));
				}
			}

			$isVote = Session::has('user.rating.comment') ? (in_array($commentId, Session::get('user.rating.comment')) ? 1 : 0) : 0;

			if (!$isVote) {
				$vote = Input::get('vote');

				$comment = Comment::findOrFail($commentId);

				if(Comment::VOTE_LIKE == $vote) {
					$comment->votes_like = $comment->votes_like + 1;
					$comment->user->setNotification(Notification::TYPE_COMMENT_LIKED);
				} elseif(Comment::VOTE_DISLIKE == $vote) {
					$comment->votes_dislike = $comment->votes_dislike + 1;
					$comment->user->setNotification(Notification::TYPE_COMMENT_DISLIKED);
				}

				if ($comment->save()) {

					// removing or adding points for comment
					if($comment->user) {
						if(($comment->votes_like - $comment->votes_dislike) == "-1") {
							if($comment->is_answer) {
								$comment->user->removePoints(User::POINTS_FOR_ANSWER);
								$comment->user->setNotification(Notification::TYPE_POINTS_FOR_ANSWER_REMOVED);
							} else {
								$comment->user->removePoints(User::POINTS_FOR_COMMENT);
								$comment->user->setNotification(Notification::TYPE_POINTS_FOR_COMMENT_REMOVED);
							}
						} elseif(($comment->votes_like - $comment->votes_dislike) == 0) {
							if($comment->is_answer) {
								$comment->user->addPoints(User::POINTS_FOR_ANSWER);
								$comment->user->setNotification(Notification::TYPE_POINTS_FOR_ANSWER_ADDED);
							} else {
								$comment->user->addPoints(User::POINTS_FOR_COMMENT);
								$comment->user->setNotification(Notification::TYPE_POINTS_FOR_COMMENT_ADDED);
							}
						}
					}

					$sessionArray = Session::get('user.rating.comment');
					$sessionArray[] = $comment->id;

					Session::put('user.rating.comment', $sessionArray);

					// return success message
					return Response::json(array(
						'success' => true,
						'votesLike' => $comment->votes_like,
						'votesDislike' => $comment->votes_dislike,
						'message' => 'Спасибо, Ваш голос принят!',
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Вы уже голосовали.',
				));
			}

		}
	}

	/**
	 * Отметить комментарий как лучший (несколько)
 	 *
	 * @param $commentId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function mark($commentId)
	{
		if(Request::ajax()) {

			$mark = Input::get('mark');

			$comment = Comment::whereId($commentId)->firstOrFail();
			if(Comment::MARK_BEST == $mark) {
				$comment->mark = $mark;

				if ($comment->save()) {

					// adding points for comment
					if($comment->mark == Comment::MARK_BEST && $comment->user) {
						$comment->user->addPoints(User::POINTS_FOR_BEST_ANSWER);
						$comment->user->setNotification(Notification::TYPE_BEST_ANSWER);
						$comment->user->setNotification(Notification::TYPE_POINTS_FOR_BEST_ANSWER_ADDED);
					}

					$bestComments = Comment::whereIsPublished(1)
						->whereParentId(0)
						->wherePageId($comment->page->id)
						->orderBy('created_at')
						->with(['user', 'publishedChildren.user'])
						->whereMark(Comment::MARK_BEST)
						->get();
					$page = $comment->page()->with('publishedComments', 'bestComments')->first();

					// return success message
					return Response::json(array(
						'success' => true,
						'message' => 'Ответ отмечен как лучший.',
						'bestCommentsHtml' => (string) View::make('widgets.comment.bestComments', compact('bestComments', 'page'))->with('isBannedIp', Ip::isBanned())->render(),
						'countComments' => count($page->publishedAnswers) - count($page->bestComments),
						'countBestComments' => count($page->bestComments),
					));
				}
			}
		}
	}

}
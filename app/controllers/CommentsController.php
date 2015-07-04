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
				'is_answer' => (Page::TYPE_QUESTION == $page->type) ? 1 : 0,
				'page_id' => $id,
				'parent_id' => $formFields['parent_id'],
				'user_id' => Auth::check() ? Auth::user()->id : null,
				'user_name' => isset($formFields['user_name']) ? $formFields['user_name'] : null,
				'ip_id' => $ip->id,
				'user_email' => isset($formFields['user_email']) ? $formFields['user_email'] : null,
				'comment' => StringHelper::nofollowLinks($formFields['comment']),
				'is_published' => Auth::check() ? 1 : 0,
				'g-recaptcha-response' => Auth::check() ? '' : Input::get('g-recaptcha-response'),
			];

			if(isset($formFields['user_name'])) {
				Session::set('user.user_name', $formFields['user_name']);
			}
			if(isset($formFields['user_email'])) {
				Session::set('user.user_email', $formFields['user_email']);
			}

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
						$comment->user->addPoints(User::POINTS_FOR_COMMENT);
					}
					// return success message
					if(Auth::check()) {
						$commentHtml = (0 == $comment->parent_id)
							? (string) View::make('widgets.comment.comment1Level', compact('comment'))->with('page', $comment->page)->render()
							: (string) View::make('widgets.comment.comment2Level')->with('page', $comment->page)->with('commentLevel2', $comment)->render();
					} else {
						$commentHtml = '';
					}
					return Response::json(array(
						'success' => true,
						'parent_id' => $comment->parent_id,
						'commentHtml' => $commentHtml,
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
				} elseif(Comment::VOTE_DISLIKE == $vote) {
					$comment->votes_dislike = $comment->votes_dislike + 1;
				}

				if ($comment->save()) {

					// removing or adding points for comment
					if($comment->user) {
						if(($comment->votes_like - $comment->votes_dislike) == "-1") {
							$comment->user->removePoints(User::POINTS_FOR_COMMENT);
						} elseif(($comment->votes_like - $comment->votes_dislike) == 0) {
							$comment->user->addPoints(User::POINTS_FOR_COMMENT);
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
						'message' => 'Спасибо, Ваш голос принят!'
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Вы уже голосовали.'
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
					}

					$bestComments = Comment::whereIsPublished(1)
						->whereParentId(0)
						->wherePageId($comment->page->id)
						->orderBy('created_at')
						->with(['user', 'publishedChildren.user'])
						->whereMark(Comment::MARK_BEST)
						->get();
					$page = $comment->page;

					// return success message
					return Response::json(array(
						'success' => true,
						'message' => 'Ответ отмечен как лучший.',
						'bestCommentsHtml' => (string) View::make('widgets.comment.bestComments', compact('bestComments', 'page'))->render(),
						'countComments' => count($comment->page->publishedComments) - count($comment->page->bestComments),
						'countBestComments' => count($comment->page->bestComments),
					));
				}
			}
		}
	}

}
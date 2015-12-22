<?php

class CommentsController extends BaseController
{
	public function addComment($id)
	{
		if(Request::ajax()) {
			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);
            $tempPath = Input::get('tempPath');

			$ip = Ip::whereIp(Request::ip())->first();
			if(!is_object($ip)) {
				$ip = Ip::create(['ip' => Request::ip()]);
			}
			$page = Page::select('id', 'user_id', 'parent_id', 'type', 'is_container', 'title')
				->with([
					'user' => function($query) {
						$query->select('id', 'login', 'alias', 'firstname', 'lastname', 'email');
					},
				])
				->findOrFail($id);

			if(Auth::check()) {
				$premoderation = Setting::whereKey('commentsPremoderationForRegistered')->select('value')->first();
			} else {
				$premoderation = Setting::whereKey('commentsPremoderationForUnregistered')->select('value')->first();
			}
			$isPublished = $premoderation->value ? 0 : 1;

			$userData = [
				'is_answer' => (Page::TYPE_QUESTION == $page->type && 0 == $formFields['parent_id']) ? 1 : 0,
				'page_id' => $id,
				'parent_id' => $formFields['parent_id'],
				'user_id' => Auth::check() ? Auth::user()->id : null,
				'user_name' => isset($formFields['user_name']) ? $formFields['user_name'] : null,
				'ip_id' => $ip->id,
				'user_email' => isset($formFields['user_email']) ? $formFields['user_email'] : null,
				'comment' => StringHelper::nofollowLinks($formFields['comment']),
				'is_published' => $isPublished,
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
                    $comment->comment = $comment->saveEditorImages($tempPath);
                    $comment->save();

					/* for notifications */
					$variable = [
						'[pageTitle]' => $comment->page->getTitle(),
						'[linkToPage]' => URL::to($comment->page->getUrl())
					];
					if($comment->is_answer) {
						$variable['[linkToAnswer]'] = URL::to($comment->getUrl());
						$variable['[answer]'] = strip_tags($comment->comment);
					} else {
						$variable['[linkToComment]'] = URL::to($comment->getUrl());
						$variable['[comment]'] = strip_tags($comment->comment);
					}
					if($comment->user) {
						if($comment->is_answer) {
							// adding points for comment
							$comment->user->addPoints(User::POINTS_FOR_ANSWER);
							$comment->user->setNotification(Notification::TYPE_POINTS_FOR_ANSWER_ADDED, $variable);
						} else {
							// adding points for comment
							$comment->user->addPoints(User::POINTS_FOR_COMMENT);
							$comment->user->setNotification(Notification::TYPE_POINTS_FOR_COMMENT_ADDED, $variable);
						}
					}

					/* уведомление автору статьи/вопроса о новом комментарии/ответе */
					$variable['[user]'] = $comment->user->login;
					$variable['[linkToUser]'] = URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]);
					if($comment->page->user->id != $comment->user->id) {
						if($comment->is_answer) {
							$comment->page->user->setNotification(Notification::TYPE_NEW_ANSWER, $variable);
						} else {
							$comment->page->user->setNotification(Notification::TYPE_NEW_COMMENT, $variable);
						}
					}

					/* уведомление админам и модераторам о новом комментарии/ответе */
					$adminsModel = User::where(function($query) use ($comment) {
						$query->whereRole(User::ROLE_ADMIN)
							->orWhereRole(User::ROLE_MODERATOR);
					})->where('id', '!=', $comment->user->id)->whereIsActive(1)->whereIsBanned(0)->get();
					foreach ($adminsModel as $admin) {
						if($comment->is_answer) {
							$admin->setNotification(Notification::TYPE_FOR_ADMIN_NEW_ANSWER, $variable);
						} else {
							$admin->setNotification(Notification::TYPE_FOR_ADMIN_NEW_COMMENT, $variable);
						}
					}

					// return success message
					if($isPublished) {
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
						'is_published' => $isPublished,
						'commentHtml' => $commentHtml,
						'message' => $isPublished
							? (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Ваш комментарий успешно отправлен!'])->render()
							: (string) View::make('widgets.siteMessages.info', ['siteMessage' => 'Ваш комментарий отправлен и будет опубликован после проверки модератором.'])->render(),
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
						'message' => (string) View::make('widgets.siteMessages.danger', ['siteMessage' => 'Вы забанены администратором сайта и не можете голосовать.'])->render(),
					));
				}
			}

			$isVote = Session::has('user.rating.comment')
				? (in_array($commentId, Session::get('user.rating.comment')) ? 1 : 0) : 0;

			if (!$isVote) {
				$vote = Input::get('vote');
				$userLogin = ('' != Input::get('userLogin'))
					? trim(Input::get('userLogin'))
					: 'Незарегистрированный пользователь';
				$linkToUser = ('' != Input::get('userLogin'))
					? URL::route('user.profile', ['login' => strtolower($userLogin)])
					: '';

				$comment = Comment::findOrFail($commentId);

				if(Comment::VOTE_LIKE == $vote) {
					$comment->votes_like = $comment->votes_like + 1;
					if($comment->user) {
						if($comment->is_answer) {
							$comment->user->setNotification(Notification::TYPE_ANSWER_LIKED, [
								'[user]' => $userLogin,
								'[linkToUser]' => $linkToUser,
								'[linkToAnswer]' => URL::to($comment->getUrl()),
								'[answer]' => strip_tags($comment->comment),
								'[pageTitle]' => $comment->page->getTitle(),
								'[linkToPage]' => URL::to($comment->page->getUrl())
							]);
						} else {
							$comment->user->setNotification(Notification::TYPE_COMMENT_LIKED, [
								'[user]' => $userLogin,
								'[linkToUser]' => $linkToUser,
								'[linkToComment]' => URL::to($comment->getUrl()),
								'[comment]' => strip_tags($comment->comment),
								'[pageTitle]' => $comment->page->getTitle(),
								'[linkToPage]' => URL::to($comment->page->getUrl())
							]);
						}
					}
				} elseif(Comment::VOTE_DISLIKE == $vote) {
					$comment->votes_dislike = $comment->votes_dislike + 1;
					if($comment->user) {
						if($comment->is_answer) {
							$comment->user->setNotification(Notification::TYPE_ANSWER_DISLIKED, [
								'[user]' => $userLogin,
								'[linkToUser]' => $linkToUser,
								'[linkToAnswer]' => URL::to($comment->getUrl()),
								'[answer]' => strip_tags($comment->comment),
								'[pageTitle]' => $comment->page->getTitle(),
								'[linkToPage]' => URL::to($comment->page->getUrl())
							]);
						} else {
							$comment->user->setNotification(Notification::TYPE_COMMENT_DISLIKED, [
								'[user]' => $userLogin,
								'[linkToUser]' => $linkToUser,
								'[linkToComment]' => URL::to($comment->getUrl()),
								'[comment]' => strip_tags($comment->comment),
								'[pageTitle]' => $comment->page->getTitle(),
								'[linkToPage]' => URL::to($comment->page->getUrl())
							]);
						}
					}
				}

				if ($comment->save()) {
					// removing or adding points for comment
					if($comment->user) {
						if(($comment->votes_like - $comment->votes_dislike) == "-1") {
							if($comment->user) {
								if($comment->is_answer) {
									$comment->user->removePoints(User::POINTS_FOR_ANSWER);
									$comment->user->setNotification(Notification::TYPE_POINTS_FOR_ANSWER_REMOVED, [
										'[linkToAnswer]' => URL::to($comment->getUrl()),
										'[answer]' => strip_tags($comment->comment),
										'[pageTitle]' => $comment->page->getTitle(),
										'[linkToPage]' => URL::to($comment->page->getUrl())
									]);
								} else {
									$comment->user->removePoints(User::POINTS_FOR_COMMENT);
									$comment->user->setNotification(Notification::TYPE_POINTS_FOR_COMMENT_REMOVED, [
										'[linkToComment]' => URL::to($comment->getUrl()),
										'[comment]' => strip_tags($comment->comment),
										'[pageTitle]' => $comment->page->getTitle(),
										'[linkToPage]' => URL::to($comment->page->getUrl())
									]);
								}
							}
						} elseif(($comment->votes_like - $comment->votes_dislike) == 0) {
							if($comment->user) {
								if($comment->is_answer) {
									$comment->user->addPoints(User::POINTS_FOR_ANSWER);
									$comment->user->setNotification(Notification::TYPE_POINTS_FOR_ANSWER_ADDED, [
										'[linkToAnswer]' => URL::to($comment->getUrl()),
										'[answer]' => strip_tags($comment->comment),
										'[pageTitle]' => $comment->page->getTitle(),
										'[linkToPage]' => URL::to($comment->page->getUrl())
									]);
								} else {
									$comment->user->addPoints(User::POINTS_FOR_COMMENT);
									$comment->user->setNotification(Notification::TYPE_POINTS_FOR_COMMENT_ADDED, [
										'[linkToComment]' => URL::to($comment->getUrl()),
										'[comment]' => strip_tags($comment->comment),
										'[pageTitle]' => $comment->page->getTitle(),
										'[linkToPage]' => URL::to($comment->page->getUrl())
									]);
								}
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
						'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Спасибо, Ваш голос принят!'])->render(),
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', ['siteMessage' => 'Вы уже голосовали.'])->render(),
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
						if($comment->user) {
							$comment->user->addPoints(User::POINTS_FOR_BEST_ANSWER);
							$variable = [
								'[linkToPage]' => URL::to($comment->page->getUrl()),
								'[pageTitle]' => $comment->page->getTitle(),
								'[linkToAnswer]' => URL::to($comment->getUrl()),
								'[answer]' => strip_tags($comment->comment),
							];
							$comment->user->setNotification(Notification::TYPE_BEST_ANSWER, $variable);
							$comment->user->setNotification(Notification::TYPE_POINTS_FOR_BEST_ANSWER_ADDED, $variable);
						}
					}

					$bestComments = Comment::whereIsPublished(1)
						->whereParentId(0)
						->wherePageId($comment->page->id)
						->orderBy('created_at')
						->with(['user', 'publishedChildren.user'])
						->whereMark(Comment::MARK_BEST)
						->get();
					$page = $comment->page()->with('publishedComments', 'bestComments')->first();

					// очистка кэша
					Cache::forget('widgets.answers');
					Cache::forget('widgets.questions');

					// return success message
					return Response::json(array(
						'success' => true,
						'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Ответ отмечен как лучший.'])->render(),
						'bestCommentsHtml' => (string) View::make('widgets.comment.bestComments', compact('bestComments', 'page'))->with('isBannedIp', Ip::isBanned())->render(),
						'countComments' => count($page->publishedAnswers) - count($page->bestComments),
						'countBestComments' => count($page->bestComments),
					));
				}
			}
		}
	}

	/**
	 * Постраничный вывод комментариев (ajax)
	 *
	 */
	public function getCommentsPage()
	{
		if(Request::ajax()) {
			$pageId = Input::get('pageId');
			$page = Page::findOrFail($pageId);

			$query = new Comment();
			$query = $query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
				->whereIsPublished(1)
				->whereParentId(0)
				->wherePageId($page->id)
				->orderBy('created_at', 'DESC')
				->with([
					'user' => function($query) {
						$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
					},
					'publishedChildren' => function($query) {
						$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
					},
					'publishedChildren.user' => function($query) {
						$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
					},
				]);
			$query = $query->whereMark(0);
			$comments = $query->paginate(Config::get('settings.commentsCountOnPage', 10));

			return Response::json(array(
				'success' => true,
				'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Ответ отмечен как лучший.'])->render(),
				'commentsListHtml' => (string) View::make('widgets.comment.commentsList', compact('comments', 'page'))->with('isBannedIp', Ip::isBanned())->render(),
				'url' => Request::get('stranitsa', 1) != 1 ? Request::fullUrl() : Request::url(),
			));
		}
	}

}
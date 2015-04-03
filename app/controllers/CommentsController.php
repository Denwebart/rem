<?php

class CommentsController extends BaseController
{
	public function addComment($id)
	{
		if(Request::ajax()) {
			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);

			$userData = array(
				'page_id' => $id,
				'parent_id' => $formFields['parent_id'],
				'user_id' => Auth::user()->id,
				'comment' => $formFields['comment'],
			);

			$validator = Validator::make($userData, Comment::$rules);

			if ($validator->fails())
				return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
				));
			else {
				//save to DB user details
				if (Comment::create($userData)) {
					//return success message
					return Response::json(array(
						'success' => true,
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

					$sessionArray = Session::get('user.rating.comment');
					$sessionArray[] = $comment->id;

					Session::put('user.rating.comment', $sessionArray);

					//return success message
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

}
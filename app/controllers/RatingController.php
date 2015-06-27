<?php

class RatingController extends BaseController {

	public function stars($id)
	{
		if(Request::ajax()) {

			$isVote = Session::has('user.rating.page') ? (in_array($id, Session::get('user.rating.page')) ? 1 : 0) : 0;
			$page = Page::findOrFail($id);

			if(Ip::isBanned()) {
				return Response::json(array(
					'success' => false,
					'rating' => $page->getRating(),
					'message' => 'Ваш IP-адрес забанен администратором сайта, вы не можете голосовать.'
				));
			}

			if(Auth::check()) {
				if(Auth::user()->is_banned) {
					return Response::json(array(
						'success' => false,
						'rating' => $page->getRating(),
						'message' => 'Вы забанены администратором сайта и не можете голосовать.'
					));
				}
			}

			if (!$isVote) {
				if(Auth::check()) {
					if(Auth::user()->is($page->user)) {
						return Response::json(array(
							'success' => false,
							'rating' => $page->getRating(),
							'message' => (Page::TYPE_QUESTION == $page->type)
								? 'Вы не можете голосовать за свой вопрос.'
								: 'Вы не можете голосовать за свою статью.',
						));
					}
				}

				$rating = Input::get('rating');

				$page->votes = $page->votes + $rating;
				$page->voters = $page->voters + 1;

				if ($page->save()) {

					$sessionArray = Session::get('user.rating.page');
					$sessionArray[] = $page->id;

					Session::put('user.rating.page', $sessionArray);

					//return success message
					return Response::json(array(
						'success' => true,
						'rating' => $page->getRating(),
						'votes' => $page->votes,
						'voters' => $page->voters,
						'message' => 'Спасибо, Ваш голос принят!',
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'rating' => $page->getRating(),
					'message' => 'Вы уже голосовали.',
				));
			}

		}
	}

}
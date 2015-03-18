<?php

class RatingController extends BaseController {

	public function stars($id)
	{
		if(Request::ajax()) {

			$isVote = Session::has('rating.page') ? (in_array($id, Session::get('rating.page')) ? 1 : 0) : 0;

			if (!$isVote) {
				$rating = Input::get('rating');

				$page = Page::findOrFail($id);
				$page->votes = $page->votes + $rating;
				$page->voters = $page->voters + 1;

				if ($page->save()) {

					$sessionArray = Session::get('rating.page');
					$sessionArray[] = $page->id;

					Session::put('rating.page', $sessionArray);

					//return success message
					return Response::json(array(
						'success' => true,
						'rating' => $page->getRating(),
						'votes' => $page->votes,
						'voters' => $page->voters,
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
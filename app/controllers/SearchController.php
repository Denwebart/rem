<?php

class SearchController extends BaseController {

	public function index()
	{
		$search = Input::get('search');

		$results = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
		    ->where('title', 'LIKE', "%$search%")
			->orWhere('menu_title', 'LIKE', "%$search%")
			->orWhere('content', 'LIKE', "%$search%")
			->paginate(10);

		View::share('search', $search);
		return View::make('search.index', compact('results'));
	}

}

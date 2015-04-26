<?php

class SearchController extends BaseController {

	public function index()
	{
		$query = trim(Input::get('query'));

		if($query) {
			$results = Page::whereIsPublished(1)
				->where('published_at', '<', date('Y-m-d H:i:s'))
				->where('title', 'LIKE', "%$query%")
				->orWhere('menu_title', 'LIKE', "%$query%")
				->orWhere('content', 'LIKE', "%$query%")
				->with('parent.parent')
				->paginate(10);
		} else {
			$results = Page::whereIsPublished(1)
				->where('published_at', '<', date('Y-m-d H:i:s'))
				->paginate(10);
		}

		View::share('query', $query);
		return View::make('search.index', compact('results'));
	}




}

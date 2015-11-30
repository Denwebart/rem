<?php

class SearchController extends BaseController
{
	public function __construct()
	{
		parent::__construct();

		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}

		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SEARCH]);
		View::share('areaWidget', $areaWidget);
	}

	public function index()
	{
		$query = trim(Input::get('query'));
		$tag = trim(Input::get('tag'));

		if($query) {
			$results = Page::select('id', 'type', 'alias', 'is_published', 'parent_id', 'is_container', 'user_id', 'title', 'menu_title', 'introtext', 'content', 'image', 'image_alt')
				->whereIsPublished(1)
				->where('published_at', '<', date('Y-m-d H:i:s'))
				->where(function($qu) use($query) {
					$qu->where('title', 'LIKE', "%$query%")
						->orWhere('menu_title', 'LIKE', "%$query%")
						->orWhere('content', 'LIKE', "%$query%");
				})
				->with([
					'parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'parent.parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'user' => function($query) {
						$query->select('id', 'login', 'alias');
					},
				])
				->paginate(10);
		} elseif($tag) {
			$results = Page::select('id', 'type', 'alias', 'is_published', 'parent_id', 'is_container', 'user_id', 'title', 'menu_title', 'introtext', 'content', 'image', 'image_alt')
				->whereIsPublished(1)
				->where('published_at', '<', date('Y-m-d H:i:s'))
				->whereHas('tags', function($q) use($tag) {
					$q->where('title', '=', $tag);
				})
				->with([
					'parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'parent.parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'user' => function($query) {
						$query->select('id', 'login', 'alias');
					},
				])
				->paginate(10);
		} else {
			$results = Page::select('id', 'type', 'alias', 'is_published', 'parent_id', 'is_container', 'user_id', 'title', 'menu_title', 'introtext', 'content', 'image', 'image_alt')
				->whereIsPublished(1)
				->where('published_at', '<', date('Y-m-d H:i:s'))
				->with([
					'parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'parent.parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'user' => function($query) {
						$query->select('id', 'login', 'alias');
					},
				])
				->paginate(10);
		}

		View::share('query', $query);
		return View::make('search.index', compact('results', 'tag'));
	}

}

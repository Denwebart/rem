<?php

class TopController extends BaseController {

	public function index()
	{
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with('parent.parent', 'user', 'tags')
			->orderBy('published_at', 'DESC')
			->paginate(10);

		return View::make('top.index', compact('pages'));
	}

}

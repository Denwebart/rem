<?php

class JournalController extends BaseController {

	public function __construct()
	{
//		$this->beforeFilter(function()
//		{
//			$urlPrevious = (Session::has('user.urlPrevious')) ? Session::get('user.urlPrevious') : URL::previous();
//
//			if(URL::current() != $urlPrevious)
//			{
//				$alias = (Route::current()->getParameter('alias')) ? Route::current()->getParameter('alias') : '/';
//
//				$page = Page::getPageByAlias($alias)->first();
//				if(is_object($page)) {
//					$page->views = $page->views + 1;
//					$page->save();
//				}
//			}
//
//			Session::put('user.urlPrevious', URL::current());
//
//		}, ['except' => ['contactPost', 'sitemapXml']]);

	}

	public function index($alias)
	{
		$page = Page::getPageByAlias($alias)->firstOrFail();

		$articles = Page::whereHas('parent', function($q) use ($page) {
			$q->where('parent_id', '=', $page->id);
		})->get();

		dd($articles);

		View::share('page', $page);
		return View::make('journal.index', compact('articles'));
	}


}

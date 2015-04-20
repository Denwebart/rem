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
		$articles = Page::whereType(Page::TYPE_ARTICLE)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', Page::getPageByAlias($alias)->firstOrFail());
		return View::make('journal.index', compact('articles'));
	}

	public function category($journalAlias, $alias)
	{
		$page = Page::getPageByAlias($alias)->firstOrFail();
		$articles = Page::whereType(Page::TYPE_ARTICLE)
			->whereParentId($page->id)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', $page);
		return View::make('journal.category', compact('articles'));
	}

	public function article($journalAlias, $categoryAlias, $alias)
	{
		View::share('page', Page::getPageByAlias($alias)->firstOrFail());
		return View::make('journal.article');
	}

}

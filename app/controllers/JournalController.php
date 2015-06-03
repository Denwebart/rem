<?php

class JournalController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter(function()
		{
			$urlPrevious = (Session::has('user.urlPrevious')) ? Session::get('user.urlPrevious') : URL::previous();

			if(URL::current() != $urlPrevious)
			{
				$alias = (Route::current()->getParameter('alias')) ? Route::current()->getParameter('alias') : '/';

				$page = Page::getPageByAlias($alias)->first();
				if(is_object($page)) {
					$page->views = $page->views + 1;
					$page->save();
				}
			}

			Session::put('user.urlPrevious', URL::current());

		});

	}

	public function index($alias)
	{
		$articles = Page::whereType(Page::TYPE_ARTICLE)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with('parent.parent', 'user', 'tags')
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', Page::getPageByAlias($alias)->firstOrFail());
		return View::make('journal.index', compact('articles'));
	}

	public function journal($journalAlias, $login)
	{
		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereLogin($login)->firstOrFail())
			: User::whereLogin($login)->firstOrFail();

		$page = new Page();
		$page->meta_title = 'Бортовой журнал пользователя ' . $user->login;
		$page->meta_desc = 'Бортовой журнал пользователя ' . $user->login;
		$page->meta_key = '';
		$page->parent = Page::getPageByAlias($journalAlias)->first();

		$articles = Page::whereType(Page::TYPE_ARTICLE)
			->whereUserId($user->id)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with('parent.parent', 'user', 'tags')
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', $page);
		return View::make('journal.journal', compact('articles', 'user'));
	}

	public function article($journalAlias, $login, $alias)
	{
		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereLogin($login)->firstOrFail())
			: User::whereLogin($login)->firstOrFail();
		$page = Page::getPageByAlias($alias)
			->whereUserId($user->id)
			->with('parent.parent', 'tags')
			->firstOrFail();
		View::share('page', $page);
		return View::make('journal.article', compact('user'));
	}

}

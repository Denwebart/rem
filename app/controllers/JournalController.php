<?php

class JournalController extends BaseController
{

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
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_JOURNAL]);
		View::share('areaWidget', $areaWidget);

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
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_USER_JOURNAL]);
		View::share('areaWidget', $areaWidget);

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

		return View::make('journal.journal', compact('articles', 'user', 'journalAlias'));
	}

	public function article($journalAlias, $login, $alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_ARTICLE_JOURNAL]);
		View::share('areaWidget', $areaWidget);

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

		return View::make('journal.article', compact('user', 'journalAlias'));
	}

	/**
	 * Список тегов в бортовом журнале
	 *
	 */
	public function tags($journalAlias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		$page = Page::whereAlias('tag')->firstOrFail();

//		$tags = Tag::has('pages')->get();

		$tagsByAlphabet = Tag::getByAlphabet();

//		echo '<pre>';
//		dd($tagsByAlphabet);

		View::share('page', $page);

		return View::make('journal.tags', compact('tagsByAlphabet', 'journalAlias'));
	}

	/**
	 * Статьи по тегу в бортовом журнале
	 *
	 */
	public function tag($journalAlias, $tag)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		$tag = Tag::whereTitle($tag)->first();
		$tags = Page::whereAlias('tag')->firstOrFail();

		$page = new Page();
		$page->title = 'Статьи по тегу "' . $tag->title . '"';
		$page->meta_title = 'Статьи по тегу "' . $tag->title . '"';
		$page->meta_desc = 'Статьи по тегу "' . $tag->title . '"';
		$page->meta_key = 'Статьи по тегу "' . $tag->title . '"';

		View::share('page', $page);

		return View::make('journal.tag', compact('tag', 'tags', 'journalAlias'));
	}

	public function tagAutocomplete() {

		$term = Input::get('term');

		$tags = Tag::where('title', 'like', "$term%")
			->get(['title', 'id', 'image']);

		$result = [];
		foreach($tags as $item) {
			$result[] = ['id' => $item->id, 'value' => $item->title, 'image' => $item->image];
		}

		return Response::json($result);
	}

}

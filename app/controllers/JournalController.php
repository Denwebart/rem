<?php

class JournalController extends BaseController
{

	public function __construct()
	{
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}

		$this->afterFilter(function()
		{
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
			->with('parent', 'user', 'tags', 'whoSaved', 'publishedComments')
			->orderBy('published_at', 'DESC')
			->paginate(10);

		$page = Page::getPageByAlias($alias)->firstOrFail();
		$page->setViews();

		View::share('page', $page);
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
		$page->meta_key = 'Бортовой журнал пользователя ' . $user->login;
		$page->parent = Page::getPageByAlias($journalAlias)->first();
		$page->title = $page->meta_title;

		if(Auth::check()){
			if(Auth::user()->getLoginForUrl() == $login || Auth::user()->isAdmin()) {
				$articles = Page::whereType(Page::TYPE_ARTICLE)
					->whereUserId($user->id)
					->with('parent.parent', 'tags', 'whoSaved', 'publishedComments', 'user')
					->orderBy('created_at', 'DESC')
					->paginate(10);
			} else {
				$articles = Page::whereType(Page::TYPE_ARTICLE)
					->whereUserId($user->id)
					->whereIsPublished(1)
					->with('parent.parent', 'tags', 'whoSaved', 'publishedComments', 'user')
					->orderBy('created_at', 'DESC')
					->paginate(10);
			}
		} else {
			$articles = Page::whereType(Page::TYPE_ARTICLE)
				->whereUserId($user->id)
				->whereIsPublished(1)
				->with('parent.parent', 'tags', 'whoSaved', 'publishedComments', 'user')
				->orderBy('created_at', 'DESC')
				->paginate(10);
		}

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

		$page->setViews();

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

		$tagsByAlphabet = Tag::getByAlphabet();

		$page = Page::whereAlias('tag')->firstOrFail();
		$page->setViews();

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

		$articles = $tag->pages()
			->with('parent', 'tags', 'whoSaved', 'publishedComments', 'user')
			->paginate(10);

		View::share('page', $page);
		return View::make('journal.tag', compact('tag', 'tags', 'journalAlias', 'articles'));
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

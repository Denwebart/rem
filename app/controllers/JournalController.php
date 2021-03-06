<?php

class JournalController extends BaseController
{

	public function __construct()
	{
		parent::__construct();

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

		$articles = Page::select(['id', 'alias', 'title', 'menu_title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content', 'image', 'image_alt'])
			->whereType(Page::TYPE_ARTICLE)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'whoSaved' => function($query) {
					$query->select('id', 'page_id');
				},
				'tags' => function($query) {
					$query->select('id', 'page_id', 'title');
				}
			])
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
				: User::whereAlias($login)->firstOrFail())
			: User::whereAlias($login)->firstOrFail();

		$page = new Page();
		$page->meta_title = 'Авторские материалы по ремонту автомобилей от пользователя ' . $user->login;
		$page->meta_desc = 'Страница пользователя ' . $user->login . ' с информацией по всем материалам (статьи, фото отчёты, видеоролики, комментарии, вопросы, ответы), которые он создал и опубликованным на страницах веб-проекта Школа авторемонта – ремонт автомобиля своими руками.';
		$page->meta_key = 'материалы пользователя по ремонту автомобиля, статьи пользователя Бортовой журнал, статьи по ремонту автомобилей, вопросы по ремонту авто, ответы пользователя на вопросы, комментарии пользователя';
		$page->title = $page->meta_title;

		$journalParent = Page::select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title')
			->getPageByAlias($journalAlias)
			->first();

		if(Auth::check()){
			if(Auth::user()->getLoginForUrl() == $login || Auth::user()->isAdmin()) {
				$articles = Page::select(['id', 'alias', 'title', 'menu_title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content', 'image', 'image_alt'])
					->whereType(Page::TYPE_ARTICLE)
					->whereUserId($user->id)
					->with('parent.parent', 'tags', 'whoSaved', 'publishedComments', 'user')
					->with([
						'parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
						},
						'parent.parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
						},
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedComments' => function($query) {
							$query->select('id', 'page_id');
						},
						'whoSaved' => function($query) {
							$query->select('id', 'page_id');
						},
						'tags' => function($query) {
							$query->select('id', 'page_id', 'title');
						}
					])
					->orderBy('created_at', 'DESC')
					->paginate(10);
			} else {
				$articles = Page::select(['id', 'alias', 'title', 'menu_title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content'])
					->whereType(Page::TYPE_ARTICLE)
					->whereUserId($user->id)
					->whereIsPublished(1)
					->with([
						'parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
						},
						'parent.parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
						},
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedComments' => function($query) {
							$query->select('id', 'page_id');
						},
						'whoSaved' => function($query) {
							$query->select('id', 'page_id');
						},
						'tags' => function($query) {
							$query->select('id', 'page_id', 'title');
						}
					])
					->orderBy('created_at', 'DESC')
					->paginate(10);
			}
		} else {
			$articles = Page::select(['id', 'alias', 'title', 'menu_title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content'])
				->whereType(Page::TYPE_ARTICLE)
				->whereUserId($user->id)
				->whereIsPublished(1)
				->with([
					'parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'parent.parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'user' => function($query) {
						$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
					},
					'publishedComments' => function($query) {
						$query->select('id', 'page_id');
					},
					'whoSaved' => function($query) {
						$query->select('id', 'page_id');
					},
					'tags' => function($query) {
						$query->select('id', 'page_id', 'title');
					}
				])
				->orderBy('created_at', 'DESC')
				->paginate(10);
		}

		View::share('metaRobots', Config::get('settings.metaRobots'));
		View::share('page', $page);
		return View::make('journal.journal', compact('articles', 'user', 'journalAlias', 'journalParent'));
	}

	public function article($journalAlias, $login, $alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_ARTICLE_JOURNAL]);
		View::share('areaWidget', $areaWidget);

		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->firstOrFail())
			: User::whereAlias($login)->firstOrFail();
		$page = Page::getPageByAlias($alias)
			->whereUserId($user->id)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
				},
				'publishedComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'whoSaved' => function($query) {
					$query->select('id', 'page_id');
				},
				'tags' => function($query) {
					$query->select('id', 'page_id', 'title');
				}
			])
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
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_CATEGORY]);
		View::share('areaWidget', $areaWidget);

		$tagsByAlphabet = Tag::getByAlphabet();

		if(!count($tagsByAlphabet)) {
			return Response::view('errors.404', [], 404);
		}

        $parent = Page::select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title')
			->whereAlias($journalAlias)
	        ->firstOrFail();
		$page = Page::whereAlias('tag')->firstOrFail();
		$page->setViews();

		View::share('page', $page);
		return View::make('journal.tags', compact('tagsByAlphabet', 'journalAlias', 'parent'));
	}

	/**
	 * Статьи по тегу в бортовом журнале
	 *
	 */
	public function tag($journalAlias, $tag)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_CATEGORY]);
		View::share('areaWidget', $areaWidget);

		$tag = Tag::whereTitle($tag)->whereHas('pages', function($query) {
			$query->whereIsPublished(1)->where('published_at', '<', date('Y-m-d H:i:s'));
		})->firstOrFail();
		$tags = Page::select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title')
			->whereAlias('tag')
			->firstOrFail();
        $tagsParent = Page::select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title')
			->whereAlias($journalAlias)
	        ->firstOrFail();

		$page = new Page();
		$page->title = 'Статьи по тегу "' . $tag->title . '"';
		$page->meta_title = 'Статьи по тегу ' . $tag->title;
		$page->meta_desc = 'Статьи по тегу ' . $tag->title;
		$page->meta_key = 'Статьи по тегу ' . $tag->title;

		View::share('metaRobots', 'noindex, nofollow');

		$tags->parent_id = $tagsParent->id;

		$articles = $tag->pages()
			->select(['id', 'alias', 'title', 'menu_title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content'])
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'whoSaved' => function($query) {
					$query->select('id', 'page_id');
				},
				'tags' => function($query) {
					$query->select('id', 'page_id', 'title');
				}
			])
			->paginate(10);

		View::share('page', $page);
		return View::make('journal.tag', compact('tag', 'tags', 'journalAlias', 'articles', 'tagsParent'));
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

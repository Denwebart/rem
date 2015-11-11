<?php

class SiteController extends BaseController {

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
		}, ['except' => ['contactPost', 'sitemapXml']]);
	}

	public function index()
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_MAIN]);
		View::share('areaWidget', $areaWidget);

		$categories = Setting::select('id', 'key', 'value')->whereKey('categoriesOnMainPage')->first();

		$articles = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content', 'image', 'image_alt'])
			->whereIn('parent_id', explode(',', $categories->value))
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.parent.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'whoSaved' => function($query) {
					$query->select('id');
				},
				'tags' => function($query) {
					$query->select('id', 'title');
				},
			])
			->whereIsContainer(0)
			->orderBy('published_at', 'DESC')
			->paginate(10);

		$page = Page::getPageByAlias()->firstOrFail();
		$page->setViews();

		View::share('page', $page);
		return View::make('site.index', compact('articles'));
	}

	public function firstLevel($alias, $suffix = null)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		$page = Page::getPageByAlias($alias)
			->with([
				'publishedComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
			])
			->whereParentId(0)->firstOrFail();

		if(!$page->is_container && is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		} elseif($page->is_container && !is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		}

		$page->setViews();

		View::share('page', $page);

		// вывод страниц блогом, учитывая подкатегории
		if($page->is_container) {
			$categoryArray = Page::select(['id', 'parent_id'])
				->whereParentId($page->id)
				->whereIsPublished(1)
				->where('published_at', '<', date('Y-m-d H:i:s'))
				->lists('id');
			if(count($categoryArray)) {
				$children = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content', 'image', 'image_alt'])
					->where(function($query) use ($categoryArray, $page){
					$query->whereIn('parent_id', $categoryArray)
						->orWhere('parent_id', $page->id);
				})->whereIsContainer(0)
					->with([
						'parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
						},
						'parent.menuItem' => function($query) {
							$query->select('id', 'page_id', 'menu_title');
						},
						'parent.parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
						},
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedComments' => function($query) {
							$query->select('id', 'page_id');
						},
						'whoSaved' => function($query) {
							$query->select('id');
						},
						'tags' => function($query) {
							$query->select('id', 'title');
						}
					])
					->whereIsPublished(1)
					->where('published_at', '<', date('Y-m-d H:i:s'))
					->orderBy('created_at', 'DESC')
					->paginate(10);
			} else {
				$children = [];
			}
			return View::make('site.category', compact('children'));
		} else {
			return View::make('site.page');
		}
	}

	public function secondLevel($categoryAlias, $alias, $suffix = null)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		$category = Page::select('id')->getPageByAlias($categoryAlias)->firstOrFail();

		$page = Page::getPageByAlias($alias)
			->whereParentId($category->id)
			->with([
				'publishedComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
			])
			->firstOrFail();

		if(!$page->is_container && is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		} elseif($page->is_container && !is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		}

		$page->setViews();

		View::share('page', $page);

		if($page->is_container) {
			$categoryArray = Page::select(['id', 'parent_id'])
				->whereParentId($page->id)
				->whereIsPublished(1)
				->where('published_at', '<', date('Y-m-d H:i:s'))
				->lists('id');
			if(count($categoryArray)) {
				$children = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters', 'introtext', 'content', 'image', 'image_alt'])
					->where(function($query) use ($categoryArray, $page){
						$query->whereIn('parent_id', $categoryArray)
							->orWhere('parent_id', $page->id);
					})->whereIsContainer(0)
					->with([
						'parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
						},
						'parent.parent' => function($query) {
							$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
						},
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedComments' => function($query) {
							$query->select('id', 'page_id');
						},
						'whoSaved' => function($query) {
							$query->select('id');
						},
						'tags' => function($query) {
							$query->select('id', 'title');
						}
					])
					->whereIsPublished(1)
					->where('published_at', '<', date('Y-m-d H:i:s'))
					->orderBy('created_at', 'DESC')
					->paginate(10);
			} else {
				$children = [];
			}

			return View::make('site.category', compact('children'));
		} else {
			return View::make('site.page');
		}
	}

	public function thirdLevel($parentCategoryAlias, $categoryAlias, $alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		$category = Page::select('id')->getPageByAlias($categoryAlias)->firstOrFail();
		$page = Page::getPageByAlias($alias)
			->whereParentId($category->id)
			->firstOrFail();

		if($parentCategoryAlias != $page->parent->parent->alias){
			return Response::view('errors.404', [], 404);
		}

		$page->setViews();

		View::share('page', $page);
		return View::make('site.page');
	}

	public function questions($alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_QUESTIONS]);
		View::share('areaWidget', $areaWidget);

		$questions = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters'])
			->whereType(Page::TYPE_QUESTION)
			->whereIsPublished(1)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedAnswers' => function($query) {
					$query->select('id', 'page_id');
				},
				'bestComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'whoSaved' => function($query) {
					$query->select('id', 'page_id');
				},
				'subscribers' => function($query) {
					$query->select('id', 'page_id');
				}
			])
			->orderBy('published_at', 'DESC')
			->paginate(10);

		$page = Page::getPageByAlias($alias)
			->with([
				'menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				}
			])
			->firstOrFail();
		$page->setViews();

		View::share('page', $page);
		return View::make('site.questions', compact('questions'));
	}

	public function questionsCategory($questionsAlias, $alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_QUESTIONS_CATEGORY]);
		View::share('areaWidget', $areaWidget);

		$page = Page::getPageByAlias($alias)
			->with([
				'menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
			])
			->firstOrFail();
		$page->setViews();

		$questions = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters'])
			->whereType(Page::TYPE_QUESTION)
			->whereParentId($page->id)
			->whereIsPublished(1)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedAnswers' => function($query) {
					$query->select('id', 'page_id');
				},
				'bestComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'whoSaved' => function($query) {
					$query->select('id', 'page_id');
				},
				'subscribers' => function($query) {
					$query->select('id', 'page_id');
				}
			])
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', $page);
		return View::make('site.questionsCategory', compact('questions'));
	}

	public function question($questionsAlias, $categoryAlias, $alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_QUESTION]);
		View::share('areaWidget', $areaWidget);

		$category = Page::select('id')->getPageByAlias($categoryAlias)->firstOrFail();
		$page = Page::getPageByAlias($alias)
			->whereParentId($category->id)
			->with([
				'menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title');
				},
				'parent.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'parent.parent.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedAnswers' => function($query) {
					$query->select('id', 'page_id');
				},
				'bestComments' => function($query) {
					$query->select('id', 'page_id');
				},
				'whoSaved' => function($query) {
					$query->select('id', 'page_id');
				},
				'subscribers' => function($query) {
					$query->select('id', 'page_id');
				}
			])->firstOrFail();
		$page->setViews();

		View::share('page', $page);
		return View::make('site.question');
	}

	public function sitemapHtml($alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		$pages = Page::whereParentId(0)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with([
				'menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'publishedChildren' => function($query) {
					$query->select('id', 'parent_id', 'user_id', 'type', 'is_container', 'alias', 'title');
				},
				'publishedChildren.menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
				'publishedChildren.user' => function($query) {
					$query->select('id', 'login', 'alias');
				},
				'publishedChildren.parent' => function($query) {
					$query->select('id', 'parent_id', 'user_id', 'type', 'is_container', 'alias', 'title');
				},
				'publishedChildren.publishedChildren' => function($query) {
					$query->select('id', 'parent_id', 'user_id', 'type', 'is_container', 'alias', 'title');
				},
				'publishedChildren.publishedChildren.parent' => function($query) {
					$query->select('id', 'parent_id', 'user_id', 'type', 'is_container', 'alias', 'title');
				},
				'publishedChildren.publishedChildren.parent.parent' => function($query) {
					$query->select('id', 'parent_id', 'user_id', 'type', 'is_container', 'alias', 'title');
				},
			])
			->get(['id', 'parent_id', 'type', 'user_id', 'is_container', 'alias', 'title']);

		$page = Page::getPageByAlias($alias)->firstOrFail();
		$page->setViews();

		View::share('page', $page);
		return View::make('site.sitemapHtml', compact('pages'));
	}

	public function sitemapXml()
	{
		$pages = Page::whereParentId(0)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with(['publishedChildren.publishedChildren.parent.parent', 'publishedChildren.parent.parent'])
			->get(['id', 'parent_id', 'type', 'user_id', 'is_container', 'alias', 'updated_at']);

		$content = View::make('site.sitemapXml', compact('pages'));
		return Response::make($content, '200')->header('Content-Type', 'text/xml');
	}

	public function contact($alias)
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		$page = Page::getPageByAlias($alias)
			->with([
				'menuItem' => function($query) {
					$query->select('id', 'page_id', 'menu_title');
				},
			])->firstOrFail();
		$page->setViews();

		View::share('page', $page);
		return View::make('site.contact');
	}

	public function contactPost()
	{
		$ip = Ip::whereIp(Request::ip())->first();
		if(!is_object($ip)) {
			$ip = Ip::create(['ip' => Request::ip()]);
		}

		$data = [
			'user_id' => Auth::check() ? Auth::user()->id : null,
			'user_name' => Input::has('user_name') ? Input::get('user_name') : null,
			'user_email' => Input::has('user_email') ? Input::get('user_email') : null,
			'ip_id' => $ip->id,
			'subject' => Input::get('subject'),
			'message' => Input::get('message'),
			'g-recaptcha-response' => Input::get('g-recaptcha-response'),
		];

		//Validate data
		$validator = Validator::make($data, Letter::$rules);
		//If everything is correct than run passes.
		if ($validator->passes())
		{
			$letter = new Letter();
			$letter->fill($data);
			if($letter->save())
			{
				$data = $letter->toArray();
				$data['message_text'] = Input::get('message');
				if(Auth::check()) {
					$data['user_name'] = Auth::user()->getFullName();
					$data['user_login'] = Auth::user()->login;
					$data['user_alias'] = Auth::user()->getLoginForUrl();
					$data['user_email'] = Auth::user()->email;
				}
				Mail::queue('emails.contactToAdmin', $data, function($message) use ($data)
				{
					if(Auth::check()) {
						$message->from(Auth::user()->email, Auth::user()->login);
					} else {
						$message->from($data['user_email'], $data['user_name']);
					}
					$message->to(Config::get('settings.adminEmail'), Config::get('settings.adminName'))->subject($data['subject']);
				});

				if(Input::get('sendCopy'))
				{
					Mail::queue('emails.contactToUser', $data, function($message) use ($data)
					{
						$message->from(Config::get('settings.adminEmail'), Config::get('settings.adminName'));
						if(Auth::check()) {
							$message->to(Auth::user()->email, Auth::user()->login)->subject(Config::get('settings.contactSubjectToUser'));
						} else {
							$message->to($data['user_email'], $data['user_name'])->subject(Config::get('settings.contactSubjectToUser'));
						}
					});
				}

				return Redirect::back()
					->with('successMessage', 'Ваше сообщение успешно отправлено. Спасибо!');
			}
		}
		else
		{
			//return contact form with errors
			return Redirect::back()->withErrors($validator)->withInput();
		}
	}

	public function error404()
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
		View::share('areaWidget', $areaWidget);

		return Response::view('errors.404', [], 404);
	}

	public function rss()
	{
//		$feed = Rss::feed('2.0', 'UTF-8');
//		$feed->channel([
//			'title' => 'Школа авторемонта',
//			'description' => 'Статьи, советы и рекомендации по ремонту и обслуживанию автомобилей своими руками',
//			'link' => Config::get('app.url'),
//		]);

		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->orderBy('published_at', 'DESC')
			->limit(10)
			->get();

		$content = View::make('site.rssXml', compact('pages'));
		return Response::make($content, '200')->header('Content-Type', 'text/xml');
	}

}

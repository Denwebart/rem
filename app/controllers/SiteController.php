<?php

class SiteController extends BaseController {

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
//					$page->updated_at = $page->updated_at;
					$page->save();
				}
			}

			Session::put('user.urlPrevious', URL::current());

		}, ['except' => ['contactPost', 'sitemapXml']]);

	}

	public function index()
	{
		$articles = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('parent_id', '!=', 0)
			->whereType(Page::TYPE_PAGE)
			->with('parent.parent')
			->whereIsContainer(0)
			->orderBy('published_at', 'DESC')
			->paginate(10);
		View::share('page', Page::getPageByAlias()->firstOrFail());
		return View::make('site.index', compact('articles'));
	}

	public function firstLevel($alias, $suffix = null)
	{
		$page = Page::getPageByAlias($alias)->whereParentId(0)->firstOrFail();

		if(!$page->is_container && is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		} elseif($page->is_container && !is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		}

		$categoryArray = $page->publishedChildren->lists('id');
		if(count($categoryArray)) {
			$children = Page::where(function($query) use ($categoryArray, $page){
				$query->whereIn('parent_id', $categoryArray)
					->orWhere('parent_id', $page->id);
			})->whereIsContainer(0)
				->with('parent.parent')
				->paginate(10);
		} else {
			$children = [];
		}

		View::share('page', $page);
		return View::make('site.page', compact('children'));
	}

	public function secondLevel($categoryAlias, $alias, $suffix = null)
	{
		$category = Page::select('id')->getPageByAlias($categoryAlias)->firstOrFail();

		$page = Page::getPageByAlias($alias)
			->whereParentId($category->id)
			->firstOrFail();

		if(!$page->is_container && is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		} elseif($page->is_container && !is_null($suffix)) {
			return Response::view('errors.404', [], 404);
		}

		$categoryArray = $page->publishedChildren->lists('id');
		if(count($categoryArray)) {
			$children = Page::where(function($query) use ($categoryArray, $page){
				$query->whereIn('parent_id', $categoryArray)
					->orWhere('parent_id', $page->id);
			})->whereIsContainer(0)
				->with('parent.parent')
				->paginate(10);
		} else {
			$children = [];
		}

		View::share('page', $page);
		return View::make('site.page', compact('children'));
	}

	public function thirdLevel($parentCategoryAlias, $categoryAlias, $alias)
	{
		$category = Page::select('id')->getPageByAlias($categoryAlias)->firstOrFail();
		$page = Page::getPageByAlias($alias)
			->whereParentId($category->id)
			->firstOrFail();

		if($parentCategoryAlias != $page->parent->parent->alias){
			return Response::view('errors.404', [], 404);
		}

		$children = [];
		View::share('page', $page);
		return View::make('site.page', compact('children'));
	}

	public function questions($alias)
	{
		$questions = Page::whereType(Page::TYPE_QUESTION)
			->whereIsPublished(1)
			->with('parent.parent', 'user', 'publishedComments', 'bestComments')
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', Page::getPageByAlias($alias)->firstOrFail());
		return View::make('site.questions', compact('questions'));
	}

	public function questionsCategory($questionsAlias, $alias)
	{
		$page = Page::getPageByAlias($alias)->firstOrFail();
		$questions = Page::whereType(Page::TYPE_QUESTION)
			->whereParentId($page->id)
			->whereIsPublished(1)
			->with('parent.parent', 'user', 'publishedComments', 'bestComments')
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', $page);
		return View::make('site.questionsCategory', compact('questions'));
	}

	public function question($questionsAlias, $categoryAlias, $alias)
	{
		$category = Page::getPageByAlias($categoryAlias)->firstOrFail();
		$page = Page::getPageByAlias($alias)
			->whereParentId($category->id)
			->with('parent.parent', 'user', 'comments', 'bestComments')
			->firstOrFail();
		View::share('page', $page);
		return View::make('site.question');
	}

	public function sitemapHtml($alias)
	{
		$pages = Page::whereParentId(0)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with(['publishedChildren.publishedChildren.parent.parent', 'publishedChildren.parent.parent'])
			->get(['id', 'parent_id', 'type', 'user_id', 'is_container', 'alias', 'menu_title', 'title']);

		View::share('page', Page::getPageByAlias($alias)->firstOrFail());
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
		View::share('page', Page::getPageByAlias($alias)->firstOrFail());
		return View::make('site.contact');
	}

	public function contactPost()
	{
		$data = [
			'user_id' => Auth::check() ? Auth::user()->id : null,
			'user_name' => Input::has('user_name') ? Input::get('user_name') : null,
			'user_email' => Input::has('user_email') ? Input::get('user_email') : null,
			'user_ip' => Request::ip(),
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
		return Response::view('errors.404', [], 404);
	}

	public function rss()
	{
		$feed = Rss::feed('2.0', 'UTF-8');
		$feed->channel([
			'title' => 'Школа авторемонта',
			'description' => 'Статьи, советы и рекомендации по ремонту и обслуживанию автомобилей своими руками',
			'link' => Config::get('app.url'),
		]);

		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->orderBy('published_at', 'DESC')
			->limit(10)
			->get();

		foreach($pages as $page) {
			$feed->item([
				'title' => $page->getTitle(),
				'description|cdata' => $page->getIntrotext(),
				'link' => URL::to($page->getUrl()),
				'author' => $page->user->getFullName(),
				'pubDate' => $page->published_at,
			]);
		}

		return Response::make($feed, 200, array('Content-Type' => 'text/xml'));
	}

}

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
					$page->save();
				}
			}

			Session::put('user.urlPrevious', URL::current());

		}, ['except' => ['contactPost', 'sitemapXml']]);

	}

	public function index()
	{
		View::share('page', Page::getPageByAlias()->firstOrFail());
		return View::make('site.index');
	}

	public function firstLevel($alias)
	{
		View::share('page', Page::getPageByAlias($alias)->whereParentId(0)->firstOrFail());
		return View::make('site.page');
	}

	public function secondLevel($categoryAlias, $alias)
	{

//				with(array('parent' => function($query)
//			{
//				$query->where('parent_id', '=', 0);
//
//			}))
//		getPageByAlias($alias)
//			->whereHas('parent', function($q)
//			{
//				return $q->where('parent_id', '=', 0);
//
//			})

//		$page = new Page;
//		$page->setTable('pages AS p');
//		$page = $page->where('alias', '=', $alias)
//			->whereHas('parent', function($query)
//			{
//				$query->table('pages AS parent')->where('parent_id', '=', 0);
//			})
//			->firstOrFail();
//			->toSql();
//		dd($page);
//		dd(DB::getQueryLog());

		$page = Page::getPageByAlias($alias)
			->firstOrFail();

//		dd($page->parent->parent_id, $page->parent->title);

		View::share('page', $page);
		return View::make('site.page');
	}

	public function thirdLevel($parentCategoryAlias, $categoryAlias, $alias)
	{
		$page = Page::getPageByAlias($alias)->firstOrFail();

		View::share('page', $page);
		return View::make('site.page');
	}

	public function questions($alias)
	{
		$questions = Page::whereType(Page::TYPE_QUESTION)
			->whereIsPublished(1)
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
			->orderBy('published_at', 'DESC')
			->paginate(10);

		View::share('page', $page);
		return View::make('site.questionsCategory', compact('questions'));
	}

	public function question($questionsAlias, $categoryAlias, $alias)
	{
		$page = Page::getPageByAlias($alias)->firstOrFail();

		View::share('page', $page);
		return View::make('site.question');
	}

	public function sitemapHtml($alias)
	{
		$pages = Page::whereParentId(0)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with(['children'])
			->get(['id', 'parent_id', 'alias', 'menu_title', 'title']);

		View::share('page', Page::getPageByAlias($alias)->firstOrFail());
		return View::make('site.sitemapHtml', compact('pages'));
	}

	public function sitemapXml()
	{
		$pages = Page::whereParentId(0)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with(['children'])
			->get(['id', 'parent_id', 'alias', 'updated_at']);

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
		//Get all the data and store it inside Store Variable
		$data = Input::all();
		//Validation rules
		$rules = [
			'name' => 'required|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u|min:3',
			'email' => 'required|email',
			'subject' => 'max:500',
			'message' => 'required|min:5',
			'g-recaptcha-response' => 'required|captcha'
		];
		//Validate data
		$validator = Validator::make($data, $rules);
		//If everything is correct than run passes.
		if ($validator->passes())
		{
			$letter = new Letter();
			$letter->fill($data);
			$letter->ip = Request::getClientIp();
			if($letter->save())
			{
				Mail::queue('emails.contactToAdmin', $data, function($message) use ($data)
				{
					$message->from($data['email'], $data['name']);
					$message->to(Config::get('settings.adminEmail'), Config::get('settings.adminName'))->subject($data['subject']);
				});

				if(Input::get('sendCopy'))
				{
					Mail::queue('emails.contactToUser', $data, function($message) use ($data)
					{
						$message->from(Config::get('settings.adminEmail'), Config::get('settings.adminName'));
						$message->to($data['email'], $data['name'])->subject(Config::get('settings.contactSubjectToUser'));
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


}

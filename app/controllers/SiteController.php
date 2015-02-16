<?php

class SiteController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter(function()
		{
			$alias = (Route::current()->getParameter('alias')) ? Route::current()->getParameter('alias') : '/';

			if(URL::current() != URL::previous())
			{
				$page = Page::getPageByAlias($alias);
				$page->views = $page->views + 1;
				$page->save();
			}

		}, ['except' => ['contactPost', 'sitemapXml']]);
	}

	public function index()
	{
		View::share('page', Page::getPageByAlias());
		return View::make('site.index');
	}

	public function firstLevel($alias)
	{
		View::share('page', Page::getPageByAlias($alias));
		return View::make('site.firstLevel');
	}

	public function secondLevel($categoryAlias, $alias)
	{
		View::share('page', Page::getPageByAlias($alias));
		return View::make('site.page');
	}

	public function thirdLevel($parentCategoryAlias, $categoryAlias, $alias)
	{
		View::share('page', Page::getPageByAlias($alias));
		return View::make('site.page');
	}

	public function sitemapHtml($alias)
	{
		$pages = Page::whereParentId(0)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->with(['children'])
			->get(['id', 'parent_id', 'alias', 'menu_title', 'title']);

		View::share('page', Page::getPageByAlias($alias));
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
		View::share('page', Page::getPageByAlias($alias));
		return View::make('site.contact');
	}

	public function contactPost()
	{
		//Get all the data and store it inside Store Variable
		$data = Input::all();
		//Validation rules
		$rules = [
			'name' => 'required|min:3',
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

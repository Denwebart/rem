<?php

class AdminArticlesController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}

		$this->beforeFilter(function()
		{
			if(Auth::user()->isModerator()) {
				return Response::view('admin::errors.403', [], 403);
			}
		}, ['only' => ['destroy']]);
	}

	/**
	 * Display a listing of pages
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$pages = Page::whereType(Page::TYPE_ARTICLE)->orderBy($sortBy, $direction)->with('parent.parent', 'user', 'relatedArticles', 'relatedQuestions')->paginate(10);
		} else {
			$pages = Page::whereType(Page::TYPE_ARTICLE)->orderBy('created_at', 'DESC')->with('parent.parent', 'user', 'relatedArticles', 'relatedQuestions')->paginate(10);
		}

		return View::make('admin::articles.index', compact('pages'));
	}

	/**
	 * Show the form for creating a new page
	 *
	 * @return Response
	 */
	public function create()
	{
		$page = new Page();
		$page->user_id = Auth::user()->id;

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.articles.index');

		return View::make('admin::articles.create', compact('page', 'backUrl'));
	}

	/**
	 * Store a newly created page in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();

		if(Input::get('is_published') && Input::get('published_at')) {
			$published_at = Input::get('published_at') . ' ' . (Input::get('publishedTime') ? Input::get('publishedTime') : Config::get('settings.defaultPublishedTime'));
			$data['published_at'] = date('Y:m:d H:i:s', strtotime($published_at));
		} elseif(Input::get('is_published') && !Input::get('published_at')) {
			$data['published_at'] = date('Y:m:d H:i:s');
		} else {
			$data['published_at'] = null;
		}

		$data['parent_id'] = Page::whereType(Page::TYPE_JOURNAL)->first()->id;
		$data['user_id'] = Auth::user()->id;
		$data['type'] = Page::TYPE_ARTICLE;

		$validator = Validator::make($data, Page::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$page = Page::create($data);

		// начисление баллов за статью, уведомление
		$page->user->addPoints(User::POINTS_FOR_ARTICLE);
		$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_ADDED, [
			'[pageTitle]' => $page->getTitle(),
			'[linkToPage]' => URL::to($page->getUrl())
		]);

		// загрузка изображения
		$page->image = $page->setImage($data['image']);
		$page->save();

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.articles.index');
		return Redirect::to($backUrl);
	}

	/**
	 * Display the specified page.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$page = Page::whereType(Page::TYPE_ARTICLE)->whereId($id)->firstOrFail();

		return View::make('admin::articles.show', compact('page'));
	}

	/**
	 * Show the form for editing the specified page.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$page = Page::whereId($id)
			->whereType(Page::TYPE_ARTICLE)
			->with('relatedArticles.parent.parent', 'relatedQuestions.parent.parent')
			->firstOrFail();

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.articles.index');

		return View::make('admin::articles.edit', compact('page', 'backUrl'));
	}

	/**
	 * Update the specified page in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$page = Page::whereType(Page::TYPE_ARTICLE)->whereId($id)->firstOrFail();

		$data = Input::all();

		if(Input::get('is_published') && Input::get('published_at')) {
			$published_at = Input::get('published_at') . ' ' . (Input::get('publishedTime') ? Input::get('publishedTime') : Config::get('settings.defaultPublishedTime'));
			$data['published_at'] = date('Y:m:d H:i:s', strtotime($published_at));
		} elseif(Input::get('is_published') && !Input::get('published_at')) {
			$data['published_at'] = \Carbon\Carbon::now();
		} else {
			$data['published_at'] = null;
		}

		$data['parent_id'] = $page->parent_id;
		$data['user_id'] = $page->user_id;
		$data['type'] = Page::TYPE_ARTICLE;

		$validator = Validator::make($data, Page::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		$data['image'] = $page->setImage($data['image']);

		$page->update($data);

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление тегов
		Tag::deleteTag($page, Input::get('tags'));
		// добавление тегов
		Tag::addTag($page, Input::get('tags'));

		$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.articles.index');
		return Redirect::to($backUrl);
	}

	/**
	 * Remove the specified page from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$page = Page::whereType(Page::TYPE_ARTICLE)->whereId($id)->firstOrFail();
		$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_REMOVED, [
			'[pageTitle]' => $page->getTitle(),
		]);
		$page->user->removePoints(User::POINTS_FOR_ARTICLE);
		$page->delete();

		return Redirect::route('admin.articles.index');
	}

	/**
	 * Таблица с подпунктами страницы
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function children($id)
	{
		$parentPage = Page::whereType(Page::TYPE_ARTICLE)->whereId($id)->firstOrFail();

		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$pages = Page::whereParentId($id)->orderBy($sortBy, $direction)->paginate(10);
		} else {
			$pages = Page::whereParentId($id)->orderBy('created_at', 'DESC')->paginate(10);
		}

		return View::make('admin::articles.index', compact('parentPage', 'pages'));
	}

}

<?php

class AdminQuestionsController extends \BaseController {

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
			$pages = Page::whereType(Page::TYPE_QUESTION)->orderBy($sortBy, $direction)->with('parent.parent', 'user', 'publishedComments', 'bestComments', 'relatedArticles', 'relatedQuestions')->paginate(10);
		} else {
			$pages = Page::whereType(Page::TYPE_QUESTION)->orderBy('created_at', 'DESC')->with('parent.parent', 'user', 'publishedComments', 'bestComments', 'relatedArticles', 'relatedQuestions')->paginate(10);
		}

		return View::make('admin::questions.index', compact('pages'));
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

		return View::make('admin::questions.create', compact('page'));
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

		$data['user_id'] = Auth::user()->id;
		$data['type'] = Page::TYPE_QUESTION;

		$validator = Validator::make($data, Page::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$page = sPage::create($data);

		// загрузка изображения
		if(isset($data['image'])){

			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $page->getTable() . '/' . $page->id . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			if($image->width() > 225) {
				$image->insert(public_path('images/watermark.png'))
					->save($imagePath . 'origin_' . $fileName)
					->resize(225, null, function ($constraint) {
						$constraint->aspectRatio();
					})
					->save($imagePath . $fileName);
			} else {
				$image->insert(public_path('images/watermark.png'))
					->save($imagePath . $fileName);
			}
			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();

			$image->crop($cropSize, $cropSize)
				->resize(50, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . 'mini_' . $fileName);

			$data['image'] = $fileName;
		}
		// загрузка изображения

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		return Redirect::route('admin.questions.index');
	}

	/**
	 * Display the specified page.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$page = Page::whereType(Page::TYPE_QUESTION)->whereId($id)->firstOrFail();

		return View::make('admin::questions.show', compact('page'));
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
			->whereType(Page::TYPE_QUESTION)
			->with('relatedArticles.parent.parent', 'relatedQuestions.parent.parent')
			->firstOrFail();

		return View::make('admin::questions.edit', compact('page'));
	}

	/**
	 * Update the specified page in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$page = Page::whereType(Page::TYPE_QUESTION)->whereId($id)->firstOrFail();

		$data = Input::all();

		if(Input::get('is_published') && Input::get('published_at')) {
			$published_at = Input::get('published_at') . ' ' . (Input::get('publishedTime') ? Input::get('publishedTime') : Config::get('settings.defaultPublishedTime'));
			$data['published_at'] = date('Y:m:d H:i:s', strtotime($published_at));
		} elseif(Input::get('is_published') && !Input::get('published_at')) {
			$data['published_at'] = date('Y:m:d H:i:s');
		} else {
			$data['published_at'] = null;
		}

		$data['user_id'] = $page->user_id;
		$data['type'] = Page::TYPE_QUESTION;

		$validator = Validator::make($data, Page::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['image'])){

			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $page->getTable() . '/' . $page->id . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			// delete old image
			if(File::exists($imagePath . $page->image)) {
				File::delete($imagePath . $page->image);
			}
			if(File::exists($imagePath . 'origin_' . $page->image)){
				File::delete($imagePath . 'origin_' . $page->image);
			}
			if(File::exists($imagePath . 'mini_' . $page->image)){
				File::delete($imagePath . 'mini_' . $page->image);
			}

			if($image->width() > 225) {
				$image->insert(public_path('images/watermark.png'))
					->save($imagePath . 'origin_' . $fileName)
					->resize(225, null, function ($constraint) {
						$constraint->aspectRatio();
					})
					->save($imagePath . $fileName);
			} else {
				$image->insert(public_path('images/watermark.png'))
					->save($imagePath . $fileName);
			}
			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();

			$image->crop($cropSize, $cropSize)
				->resize(50, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . 'mini_' . $fileName);

			$data['image'] = $fileName;
		} else {
			$data['image'] = $page->image;
		}
		// загрузка изображения

		$page->update($data);

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		return Redirect::route('admin.questions.index');
	}

	/**
	 * Remove the specified page from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$page = Page::whereType(Page::TYPE_QUESTION)->whereId($id)->firstOrFail();
		$page->delete();

		return Redirect::route('admin.questions.index');
	}

	/**
	 * Таблица с подпунктами страницы
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function children($id)
	{
		$parentPage = Page::whereType(Page::TYPE_QUESTION)->whereId($id)->firstOrFail();

		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$pages = Page::whereParentId($id)->orderBy($sortBy, $direction)->paginate(10);
		} else {
			$pages = Page::whereParentId($id)->orderBy('created_at', 'DESC')->paginate(10);
		}

		return View::make('admin::questions.index', compact('parentPage', 'pages'));
	}

}

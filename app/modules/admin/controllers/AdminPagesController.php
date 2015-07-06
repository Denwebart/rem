<?php

class AdminPagesController extends \BaseController {

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
			$pages = Page::orderBy($sortBy, $direction)->with('parent.parent', 'children', 'relatedArticles', 'relatedQuestions')->paginate(10);
		} else {
			$pages = Page::orderBy('created_at', 'DESC')->with('parent.parent', 'children', 'relatedArticles', 'relatedQuestions')->paginate(10);
		}

		return View::make('admin::pages.index', compact('pages'));
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

		return View::make('admin::pages.create', compact('page'));
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

		if(Page::whereType(Page::TYPE_JOURNAL)->first()->id == $data['parent_id']) {
			$data['type'] = Page::TYPE_ARTICLE;
		} elseif(Page::whereType(Page::TYPE_QUESTIONS)->first()->id == $data['parent_id']) {
			$data['type'] = Page::TYPE_QUESTION;
		}

		$data['user_id'] = Auth::user()->id;

		$validator = Validator::make($data, Page::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$page = Page::create($data);

		// загрузка изображения
		if(isset($data['image'])){

			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $page->getTable() . '/' . $page->id . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			if($image->width() > 225) {
				$image->save($imagePath . 'origin_' . $fileName)
					->resize(225, null, function ($constraint) {
						$constraint->aspectRatio();
					})
					->save($imagePath . $fileName);
			} else {
				$image->save($imagePath . $fileName);
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

		return Redirect::route('admin.pages.index');
	}

	/**
	 * Display the specified page.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$page = Page::findOrFail($id);

		return View::make('admin::pages.show', compact('page'));
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
			->with('relatedArticles.parent.parent', 'relatedQuestions.parent.parent')
			->firstOrFail();

		return View::make('admin::pages.edit', compact('page'));
	}

	/**
	 * Update the specified page in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$page = Page::findOrFail($id);

		$data = Input::all();

		if(Input::get('is_published') && Input::get('published_at')) {
			$published_at = Input::get('published_at') . ' ' . (Input::get('publishedTime') ? Input::get('publishedTime') : Config::get('settings.defaultPublishedTime'));
			$data['published_at'] = date('Y:m:d H:i:s', strtotime($published_at));
		} elseif(Input::get('is_published') && !Input::get('published_at')) {
			$data['published_at'] = date('Y:m:d H:i:s');
		} else {
			$data['published_at'] = null;
		}

		if(Page::whereType(Page::TYPE_JOURNAL)->first()->id == $data['parent_id']) {
			$data['type'] = Page::TYPE_ARTICLE;
		} elseif(Page::whereType(Page::TYPE_QUESTIONS)->first()->id == $data['parent_id']) {
			$data['type'] = Page::TYPE_QUESTION;
		}

		$data['user_id'] = $page->user_id;

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
			File::exists($imagePath) or File::makeDirectory($imagePath);

			if($image->width() > 225) {
				$image->save($imagePath . 'origin_' . $fileName)
					->resize(225, null, function ($constraint) {
						$constraint->aspectRatio();
					})
					->save($imagePath . $fileName);
			} else {
				$image->save($imagePath . $fileName);
			}
			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();

			$image->crop($cropSize, $cropSize)
				->resize(50, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . 'mini_' . $fileName);

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

			$data['image'] = $fileName;
		}
		// загрузка изображения

		$page->update($data);

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		return Redirect::route('admin.pages.index');
	}

	/**
	 * Remove the specified page from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Page::destroy($id);

		return Redirect::back();
	}

	/**
	 * Открытие дерева
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function openTree() {
		if(Request::ajax()) {
			$parentId = Input::get('pageId');

			$pages = Page::whereParentId($parentId)
				->with('children')
				->get(['id', 'title', 'menu_title', 'is_published', 'is_container']);

			return Response::json(array(
				'success' => true,
				'children' => (string) View::make('admin::pages._children', compact('pages'))->render(),
			));
		}
	}

	/**
	 * Таблица с подпунктами страницы
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function children($id)
	{
		$parentPage = Page::find($id);

		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$pages = Page::whereParentId($id)->orderBy($sortBy, $direction)->paginate(10);
		} else {
			$pages = Page::whereParentId($id)->orderBy('created_at', 'DESC')->paginate(10);
		}

		return View::make('admin::pages.index', compact('parentPage', 'pages'));
	}

	public function articlesAutocomplete() {
		$term = Input::get('term');

		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where(function ($q) {
				$q->whereType(Page::TYPE_ARTICLE)
					->orWhere(function ($query) {
						$query->where('type', '=', Page::TYPE_PAGE)
							->whereIsContainer(0)
							->where('parent_id', '!=', 0);
					});
			})
			->where('title', 'like', "%$term%")
			->get(['title', 'id']);

		$result = [];
		foreach($pages as $item) {
			$result[] = ['id' => $item->id, 'value' => $item->title];
		}

		return Response::json($result);
	}

	public function questionsAutocomplete() {
		$term = Input::get('term');

		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereType(Page::TYPE_QUESTION)
			->where('title', 'like', "%$term%")
			->get(['title', 'id']);

		$result = [];
		foreach($pages as $item) {
			$result[] = ['id' => $item->id, 'value' => $item->title];
		}

		return Response::json($result);
	}

	public function checkRelated() {
		if(Request::ajax()) {

			if(RelatedPage::TYPE_QUESTION == Input::get('typeId')) {
				$addedPage = Page::whereId(Input::get('addedPageId'))
					->whereIsPublished(1)
					->where('published_at', '<', date('Y-m-d H:i:s'))
					->whereType(Page::TYPE_QUESTION)
					->first();
			} else {
				$addedPage = Page::whereId(Input::get('addedPageId'))
					->whereIsPublished(1)
					->where('published_at', '<', date('Y-m-d H:i:s'))
					->where(function ($q) {
						$q->whereType(Page::TYPE_ARTICLE)
							->orWhere(function ($query) {
								$query->where('type', '=', Page::TYPE_PAGE)
									->whereIsContainer(0)
									->where('parent_id', '!=', 0);
							});
					})
					->first();
			}

			if(is_object($addedPage)) {
				return Response::json(array(
						'success' => true,
						'message' => 'Страница существует!',
						'pageUrl' => URL::to($addedPage->getUrl()),
					));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Такой страницы нет!',
				));
			}
		}
	}
}

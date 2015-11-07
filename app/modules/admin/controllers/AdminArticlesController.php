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
        $author = Request::get('author');
        $searchQuery = Request::get('query');

        $query = new Page;
        $query = $query->whereType(Page::TYPE_ARTICLE);
        $query = $query->with('parent', 'user');
        if ($author) {
            $name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ.@_ \-\']+$/u%', '', $author))));
            $query = $query->whereHas('user', function($q) use ($name) {
                $q->where(function($qu) use ($name) {
                    $qu->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$name%")
                        ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$name%")
                        ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$name%")
                        ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$name%")
                        ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$name%")
                        ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$name%")
                        ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$name%")
                        ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$name%");
                });
            });
        }
        if ($searchQuery) {
            $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($q) use($title) {
                $q->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                    ->orWhere(DB::raw('LOWER(meta_title)'), 'LIKE', "%$title%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $pages = $query->paginate(10);

		return View::make('admin::articles.index', compact('pages'));
	}

    /**
     * Поиск статей
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search() {
        if(Request::ajax()) {
            $inputData = Request::get('searchData');
            parse_str($inputData, $data);

            $sortBy = isset($data['sortBy']) ? $data['sortBy'] : null;
            $direction = isset($data['direction']) ? $data['direction'] : null;
            $author = $data['author'];
            $searchQuery = $data['query'];

            $query = new Page;
            $query = $query->whereType(Page::TYPE_ARTICLE);
            $query = $query->with('parent', 'user');
            if ($author) {
                $name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ.@_ \-\']+$/u%', '', $author))));
                $query = $query->whereHas('user', function($q) use ($name) {
                    $q->where(function($qu) use ($name) {
                        $qu->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$name%");
                    });
                });
            }
            if ($searchQuery) {
                $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($q) use($title) {
                    $q->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                        ->orWhere(DB::raw('LOWER(meta_title)'), 'LIKE', "%$title%");
                });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $pages = $query->paginate(10);

            $url = URL::route('admin.articles.index', $data);
            Session::set('user.url', $url);

            return Response::json([
                'success' => true,
                'url' => $url,
                'pagesListHtmL' => (string) View::make('admin::articles.list', compact('pages', 'url'))->render(),
                'pagesPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $pages)->render(),
                'pagesCountHtmL' => (string) View::make('admin::parts.count')->with('models', $pages)->render(),
            ]);
        }
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

		$data['parent_id'] = Page::whereType(Page::TYPE_JOURNAL)->first()->id;
		$data['user_id'] = Auth::user()->id;
		$data['type'] = Page::TYPE_ARTICLE;
		$data['alias'] = $data['alias'] ? $data['alias'] : TranslitHelper::make($data['title']);

		$validator = Validator::make($data, Page::rules('create'));

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput($data);
		}

		if(Input::get('is_published') && Input::get('published_at')) {
			$published_at = Input::get('published_at') . ' ' . (Input::get('publishedTime') ? Input::get('publishedTime') : Config::get('settings.defaultPublishedTime'));
			$data['published_at'] = date('Y:m:d H:i:s', strtotime($published_at));
		} elseif(Input::get('is_published') && !Input::get('published_at')) {
			$data['published_at'] = date('Y:m:d H:i:s');
		} else {
			$data['published_at'] = null;
		}

		$page = Page::create($data);

		if($page->is_published) {
			// начисление баллов за статью, уведомление
			$page->user->addPoints(User::POINTS_FOR_ARTICLE);
			$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_ADDED, [
				'[pageTitle]' => $page->getTitle(),
				'[linkToPage]' => URL::to($page->getUrl())
			]);
		}

		// загрузка изображения
		$page->image = $page->setImage($data['image']);
		$page->content = $page->saveEditorImages($data['tempPath']);
		$page->introtext = $page->saveEditorImages($data['tempPath'], 'introtext');
		$page->save();

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		if($page->is_published) {
			$backUrl = URL::to($page->getUrl());
		} else {
			$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.articles.index');
		}

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

		$data['parent_id'] = $page->parent_id;
		$data['user_id'] = $page->user_id;
		$data['type'] = Page::TYPE_ARTICLE;
		$data['alias'] = $data['alias'] ? $data['alias'] : TranslitHelper::make($data['title']);

		$validator = Validator::make($data, Page::rules('update', 'forAdmin', $page->id));

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput($data);
		}

		if(Input::get('is_published') && Input::get('published_at')) {
			$published_at = Input::get('published_at') . ' ' . (Input::get('publishedTime') ? Input::get('publishedTime') : Config::get('settings.defaultPublishedTime'));
			$data['published_at'] = date('Y:m:d H:i:s', strtotime($published_at));
		} elseif(Input::get('is_published') && !Input::get('published_at')) {
			$data['published_at'] = \Carbon\Carbon::now();
		} else {
			$data['published_at'] = null;
		}

		// загрузка изображения
		$data['image'] = $page->setImage($data['image']);

		$publishedStatusBeforeSave = $page->is_published;
		$page->update($data);

		$page->content = $page->saveEditorImages($data['tempPath']);
        $page->introtext = $page->saveEditorImages($data['tempPath'], 'introtext');
		$page->save();

		if ($publishedStatusBeforeSave == 0 && $page->is_published == 1) {
			// начисление баллов за статью, уведомление
			$page->user->addPoints(User::POINTS_FOR_ARTICLE);
			$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_ADDED, [
				'[pageTitle]' => $page->getTitle(),
				'[linkToPage]' => URL::to($page->getUrl())
			]);
		} elseif($publishedStatusBeforeSave == 1 && $page->is_published == 0) {
			// вычтание баллов за статью, уведомление
			$page->user->removePoints(User::POINTS_FOR_ARTICLE);
			$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_REMOVED, [
				'[pageTitle]' => $page->getTitle(),
				'[linkToPage]' => URL::to($page->getUrl())
			]);
		}

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
		if($page->is_published) {
			$page->user->removePoints(User::POINTS_FOR_ARTICLE);
		}
		$page->delete();

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.articles.index');
        return Redirect::to($backUrl);
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

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
        $parent_id = Request::get('parent_id');
        $author = Request::get('author');
        $searchQuery = Request::get('query');

        $limit = 10;
        $relations = ['parent.parent', 'user', 'publishedAnswers', 'bestComments'];

        $query = new Page;
        $query = $query->whereType(Page::TYPE_QUESTION);
        $query = $query->with($relations);
        if ($parent_id) {
            $query = $query->whereParentId($parent_id);
            $parentPage = Page::find($parent_id);
        } else {
            $parentPage = null;
        }
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
            if(in_array($sortBy, $relations)) {
                $page = Request::get('stranitsa', 1);

                if($direction == 'asc') {
                    $pages = $query->skip($limit * ($page - 1))->take($limit)->get()
                        ->sortBy(function($q) use($sortBy) {
                            return $q->$sortBy->count();
                        });
                } else {
                    $pages = $query->sortBy(function($q) use($sortBy) {
                            return $q->$sortBy->count();
                        })->reverse();
                }
                $pages = Paginator::make($pages->all(), count($pages), $limit);
            } else {
                $query = $query->orderBy($sortBy, $direction);
                $pages = $query->paginate($limit);
            }
        } else {
            $query = $query->orderBy('created_at', 'DESC');
            $pages = $query->paginate($limit);
        }

		return View::make('admin::questions.index', compact('pages', 'parentPage'));
	}

    /**
     * Поиск вопросов
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search() {
        if(Request::ajax()) {
            $inputData = Request::get('searchData');
            parse_str($inputData, $data);

            $sortBy = isset($data['sortBy']) ? $data['sortBy'] : null;
            $direction = isset($data['direction']) ? $data['direction'] : null;
            $parent_id = $data['parent_id'];
            $author = $data['author'];
            $searchQuery = $data['query'];

            $limit = 10;
            $relations = ['parent.parent', 'user', 'publishedAnswers', 'bestComments'];

            $query = new Page;
            $query = $query->whereType(Page::TYPE_QUESTION);
            $query = $query->with($relations);
            if ($parent_id) {
                $query = $query->whereParentId($parent_id);
                $parentPage = Page::find($parent_id);
            } else {
                $parentPage = null;
            }
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
                if(in_array($sortBy, $relations)) {
                    $page = Request::get('stranitsa', 1);

                    if($direction == 'asc') {
                        $pages = $query->skip($limit * ($page - 1))->take($limit)->get()
                            ->sortBy(function($user) use($sortBy) {
                                return $user->$sortBy->count();
                            });
                        $pages = Paginator::make($pages->all(), count($pages), $limit);
                    } else {
                        $pages = $query->skip($limit * ($page - 1))->take($limit)->get()
                            ->sortBy(function($user) use($sortBy) {
                                return $user->$sortBy->count();
                            })->reverse();
                        $pages = Paginator::make($pages->all(), count($pages), $limit);
                    }
                } else {
                    $query = $query->orderBy($sortBy, $direction);
                    $pages = $query->paginate($limit);
                }
            } else {
                $query = $query->orderBy('created_at', 'DESC');
                $pages = $query->paginate($limit);
            }

            $url = URL::route('admin.questions.index', $data);
            Session::set('user.url', $url);

            return Response::json([
                'success' => true,
                'url' => $url,
                'pagesListHtmL' => (string) View::make('admin::questions.list', compact('pages', 'url'))->render(),
                'pagesPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $pages)->render(),
                'pagesCountHtmL' => (string) View::make('admin::parts.count')->with('models', $pages)->render(),
                'pagesTitleHtmL' => (string) View::make('admin::questions.title', compact('parentPage'))->render(),
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
			: URL::route('admin.questions.index');

		return View::make('admin::questions.create', compact('page', 'backUrl'));
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
			$data['published_at'] = \Carbon\Carbon::now();
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

		$page = Page::create($data);

		// начисление баллов за вопрос
		$page->user->addPoints(User::POINTS_FOR_QUESTION);

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

		$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.questions.index');
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

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.questions.index');

		return View::make('admin::questions.edit', compact('page', 'backUrl'));
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
		$data['image'] = $page->setImage($data['image']);

		$page->update($data);

		$page->content = $page->saveEditorImages($data['tempPath']);
        $page->introtext = $page->saveEditorImages($data['tempPath'], 'introtext');
		$page->save();

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.questions.index');
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
		$page = Page::whereType(Page::TYPE_QUESTION)->whereId($id)->firstOrFail();
		$page->user->setNotification(Notification::TYPE_QUESTION_DELETED, [
			'[pageTitle]' => $page->getTitle(),
		]);
		$page->user->removePoints(User::POINTS_FOR_QUESTION);
		$page->delete();

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.questions.index');
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

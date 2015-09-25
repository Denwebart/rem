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
        $parent_id = Request::get('parent_id');

        $query = new Page;
        $query = $query->with('parent.parent', 'children', 'relatedArticles', 'relatedQuestions');
        if($parent_id) {
            $query = $query->whereParentId($parent_id);
            $parentPage = Page::find($parent_id);
        } else {
            $parentPage = null;
        }
        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $pages = $query->paginate(10);

		return View::make('admin::pages.index', compact('pages', 'parentPage'));
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
			: URL::route('admin.pages.index');

		return View::make('admin::pages.create', compact('page', 'backUrl'));
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

		if(Page::whereType(Page::TYPE_JOURNAL)->first()->id == $data['parent_id']) {
			$data['type'] = Page::TYPE_ARTICLE;
		} elseif(Page::whereType(Page::TYPE_QUESTIONS)->first()->id == $data['parent_id']) {
			$data['type'] = Page::TYPE_QUESTION;
		} else {
			$data['type'] = Page::TYPE_PAGE;
		}

		$data['user_id'] = Auth::user()->id;

		$validator = Validator::make($data, Page::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$page = Page::create($data);

		// начисление баллов за статью, уведомление
		if(Page::TYPE_QUESTION == $page->type) {
			$page->user->addPoints(User::POINTS_FOR_QUESTION);
		} else {
			$page->user->addPoints(User::POINTS_FOR_ARTICLE);
			$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_ADDED, [
				'[pageTitle]' => $page->getTitle(),
				'[linkToPage]' => URL::to($page->getUrl())
			]);
		}

		// загрузка изображения
		$page->image = $page->setImage($data['image']);
		$page->save();

		// добавление похожих статей, вопросов
		RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.pages.index');
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

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.pages.index');

		return View::make('admin::pages.edit', compact('page', 'backUrl'));
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
		} else {
			$data['type'] = Page::TYPE_PAGE;
		}

		$data['user_id'] = $page->user_id;

		if(1 != $page->id) {
			$rules = Page::$rules;
		} else {
			unset(Page::$rules['alias']);
			$rules = Page::$rules + ['alias' => 'max:300|regex:#^[A-Za-z0-9\-\'/]+$#u'];
		}

		$validator = Validator::make($data, $rules);
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

		$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.pages.index');
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
		$page = Page::find($id);
		if(Page::TYPE_QUESTION == $page->type) {
			$page->user->setNotification(Notification::TYPE_QUESTION_DELETED, [
				'[pageTitle]' => $page->getTitle(),
			]);
			$page->user->removePoints(User::POINTS_FOR_QUESTION);
		} else {
			$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_REMOVED, [
				'[pageTitle]' => $page->getTitle(),
			]);
			$page->user->removePoints(User::POINTS_FOR_ARTICLE);
		}
		$page->delete();

		return Redirect::back();
	}

	/**
	 * Удаление изображения из таблицы Page
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteImage($id) {
		if(Request::ajax())
		{
			$page = Page::findOrFail($id);
			$page->deleteImage();

			return Response::json([
				'success' => true,
			]);
		}
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
				->whereIsContainer(1)
				->get(['id', 'title', 'menu_title', 'is_published', 'is_container']);

			return Response::json(array(
				'success' => true,
				'children' => (string) View::make('admin::pages._children', compact('pages'))->render(),
				'childrenCount' => count($pages)
			));
		}
	}

	/**
	 * Таблица с подпунктами страницы
	 *
	 * @param  int  $id
	 * @return Response
	 */
//	public function children($id)
//	{
//		$parentPage = Page::find($id);
//
//		$sortBy = Request::get('sortBy');
//		$direction = Request::get('direction');
//		if ($sortBy && $direction) {
//			$pages = Page::whereParentId($id)->orderBy($sortBy, $direction)->paginate(10);
//		} else {
//			$pages = Page::whereParentId($id)->orderBy('created_at', 'DESC')->paginate(10);
//		}
//
//		return View::make('admin::pages.index', compact('parentPage', 'pages'));
//	}

    /**
     * Поиск статей
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search() {
//        $term = Input::get('term');
//
//        $pages = Page::whereIsPublished(1)
//            ->where('published_at', '<', date('Y-m-d H:i:s'))
//            ->where(function ($q) {
//                $q->whereType(Page::TYPE_ARTICLE)
//                    ->orWhere(function ($query) {
//                        $query->where('type', '=', Page::TYPE_PAGE)
//                            ->whereIsContainer(0)
//                            ->where('parent_id', '!=', 0);
//                    });
//            })
//            ->where('title', 'like', "%$term%")
//            ->get(['title', 'id']);
//
//        $result = [];
//        foreach($pages as $item) {
//            $result[] = ['id' => $item->id, 'value' => $item->title];
//        }

        if(Request::ajax()) {
            $inputData = Request::get('searchData');
            parse_str($inputData, $data);

            $sortBy = isset($data['sortBy']) ? $data['sortBy'] : null;
            $direction = isset($data['direction']) ? $data['direction'] : null;
            $parent_id = $data['parent_id'];

            $query = new Page;
            $query = $query->with('parent.parent', 'children', 'relatedArticles', 'relatedQuestions');
            if ($parent_id) {
                $query = $query->whereParentId($parent_id);
                $parentPage = Page::find($parent_id);
            } else {
                $parentPage = null;
            }
            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $pages = $query->paginate(10);
            $pagesList = (string) View::make('admin::pages.list', compact('pages'))->render();
            $pagesPagination = (string) View::make('admin::pages.pagination', compact('pages'))->render();
            $pagesCount = (string) View::make('admin::pages.count', compact('pages'))->render();
            $pagesTitle = (string) View::make('admin::pages.title', compact('parentPage'))->render();

            return Response::json([
                'success' => true,
                'pagesListHtmL' => $pagesList,
                'pagesPaginationHtmL' => $pagesPagination,
                'pagesCountHtmL' => $pagesCount,
                'pagesTitleHtmL' => $pagesTitle,
            ]);
        }
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

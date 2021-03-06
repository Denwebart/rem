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
        $author = Request::get('author');
        $searchQuery = Request::get('query');

        $query = new Page;
        $query = $query->with('parent.parent', 'children', 'user', 'relatedArticles', 'relatedQuestions');
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
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $pages = $query->paginate(10);

		return View::make('admin::pages.index', compact('pages', 'parentPage'));
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
            $parent_id = $data['parent_id'];
            $author = $data['author'];
            $searchQuery = $data['query'];

            $query = new Page;
            $query = $query->with('parent.parent', 'children', 'user', 'relatedArticles', 'relatedQuestions');
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
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $pages = $query->paginate(10);
            if(Request::has('route')) {
                $pages->setBaseUrl('/admin/pages/' . Request::get('route'));
            } else {
                $pages->setBaseUrl('/admin/pages');
            }

            $view = Request::has('view') ? Request::get('view') : 'list';
            $route = Request::has('route') ? Request::get('route') : 'index';
			$url = URL::route('admin.pages.' . $route, $data);

            Session::set('user.url', $url);

            return Response::json([
                'success' => true,
                'url' => $url,
                'pagesListHtmL' => (string) View::make('admin::pages.' . $view, compact('pages', 'url'))->render(),
                'pagesPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $pages)->render(),
                'pagesCountHtmL' => (string) View::make('admin::parts.count')->with('models', $pages)->render(),
                'pagesTitleHtmL' => (string) View::make('admin::pages.title', compact('parentPage'))->render(),
            ]);
        }
    }

    public function metadata()
    {
        $sortBy = Request::get('sortBy');
        $direction = Request::get('direction');
        $parent_id = Request::get('parent_id');
        $author = Request::get('author');
        $searchQuery = Request::get('query');

        $query = new Page;
        $query = $query->with('parent.parent', 'children', 'user', 'relatedArticles', 'relatedQuestions');
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
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $pages = $query->paginate(10);

        return View::make('admin::pages.metadata', compact('pages', 'parentPage'));
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
		$page->is_show_title = 1;

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

		$data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : 0;
		$parent = Page::find($data['parent_id']);
		$parentParentId = $parent ? $parent->parent_id : 0;
		$questions = Page::whereType(Page::TYPE_QUESTIONS)->first();

		if(Page::whereType(Page::TYPE_JOURNAL)->first()->id == $data['parent_id']) {
			$data['type'] = Page::TYPE_ARTICLE;
		} elseif($questions->id == $data['parent_id']) {
			if($data['is_container'] == 1) {
				$data['type'] = Page::TYPE_PAGE;
			} else {
				$data['type'] = Page::TYPE_QUESTION;
			}
		} elseif($questions->id == $parentParentId) {
			if($data['is_container'] == 1) {
				$data['type'] = Page::TYPE_PAGE;
			} else {
				$data['type'] = Page::TYPE_QUESTION;
			}
		} else {
			$data['type'] = Page::TYPE_PAGE;
		}

		$data['user_id'] = Auth::user()->id;
		$data['alias'] = isset($data['alias']) ? $data['alias'] : TranslitHelper::make($data['title']);

		$validator = Validator::make($data, Page::rules('create'));

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

		$page = Page::create($data);

		if($page->is_published) {
			// начисление баллов за статью, уведомление
			if(Page::TYPE_QUESTION == $page->type) {
				$page->user->addPoints(User::POINTS_FOR_QUESTION);
			} elseif(Page::TYPE_ARTICLE == $page->type) {
				$page->user->addPoints(User::POINTS_FOR_ARTICLE);
				$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_ADDED, [
					'[pageTitle]' => $page->getTitle(),
					'[linkToPage]' => URL::to($page->getUrl())
				]);
			}
		}

		// загрузка изображения
		$page->image = $page->setImage($data['image']);
		$page->content = $page->saveEditorImages($data['tempPath']);
        $page->introtext = $page->saveEditorImages($data['tempPath'], 'introtext');
		$page->save();

        // удаление тегов
        Tag::deleteTag($page, Input::get('tags'));
        // добавление тегов
        Tag::addTag($page, Input::get('tags'));

		// удаление похожих статей, вопросов
		RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
		RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

        // добавление похожих статей, вопросов
        RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
        RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// добавление в меню
		Menu::inMenu($page);

//		if($page->is_published) {
//			$backUrl = URL::to($page->getUrl());
//		} else {
			$backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.pages.index');
//		}

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

		if(Page::TYPE_SYSTEM_PAGE != $page->type && Page::TYPE_QUESTIONS != $page->type && Page::TYPE_JOURNAL != $page->type) {
			$data['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : 0;
			$parent = Page::find($data['parent_id']);
			$parentParentId = $parent ? $parent->parent_id : 0;
			$questions = Page::whereType(Page::TYPE_QUESTIONS)->first();

			if(Page::whereType(Page::TYPE_JOURNAL)->first()->id == $data['parent_id']) {
				$data['type'] = Page::TYPE_ARTICLE;
			} elseif($questions->id == $data['parent_id']) {
				if($data['is_container'] == 1) {
					$data['type'] = Page::TYPE_PAGE;
				} else {
					$data['type'] = Page::TYPE_QUESTION;
				}
			} elseif($questions->id == $parentParentId) {
				if($data['is_container'] == 1) {
					$data['type'] = Page::TYPE_PAGE;
				} else {
					$data['type'] = Page::TYPE_QUESTION;
				}
			} else {
				$data['type'] = Page::TYPE_PAGE;
			}
		}

		$data['user_id'] = $page->user_id;
		if(Page::TYPE_SYSTEM_PAGE != $page->type && Page::TYPE_QUESTIONS != $page->type && Page::TYPE_JOURNAL != $page->type){
			$data['alias'] = isset($data['alias'])
				? $data['alias']
				: ($data['menu_title']
					? TranslitHelper::make($data['menu_title'])
					: TranslitHelper::make($data['title']));
		}

		$rules = Page::rules('update', 'forAdmin', $page->id);
		if(1 != $page->id) {
			$rules = $rules;
		} else {
			$rules['alias'] = 'max:300|regex:#^[A-Za-z0-9\-\'/]+$#u';
		}

		$validator = Validator::make($data, $rules);
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

		// загрузка изображения
		$data['image'] = $page->setImage($data['image']);

		$publishedStatusBeforeSave = $page->is_published;
		$page->update($data);

		$page->content = $page->saveEditorImages($data['tempPath']);
        $page->introtext = $page->saveEditorImages($data['tempPath'], 'introtext');
		$page->save();

		if ($publishedStatusBeforeSave == 0 && $page->is_published == 1) {
			// начисление баллов за статью, уведомление
			if(Page::TYPE_QUESTION == $page->type) {
				$page->user->addPoints(User::POINTS_FOR_QUESTION);
			} elseif(Page::TYPE_ARTICLE == $page->type) {
				$page->user->addPoints(User::POINTS_FOR_ARTICLE);
				$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_ADDED, [
					'[pageTitle]' => $page->getTitle(),
					'[linkToPage]' => URL::to($page->getUrl())
				]);
			}
		} elseif($publishedStatusBeforeSave == 1 && $page->is_published == 0) {
			// вычтание баллов за статью, уведомление
			if(Page::TYPE_ARTICLE == $page->type) {
				$page->user->removePoints(User::POINTS_FOR_ARTICLE);
				$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_REMOVED, [
					'[pageTitle]' => $page->getTitle(),
					'[linkToPage]' => URL::to($page->getUrl())
				]);
			}
		}

        // удаление тегов
        Tag::deleteTag($page, Input::get('tags'));
        // добавление тегов
        Tag::addTag($page, Input::get('tags'));

        // удаление похожих статей, вопросов
        RelatedPage::deleteRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
        RelatedPage::deleteRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

        // добавление похожих статей, вопросов
        RelatedPage::addRelated($page, Input::get('relatedarticles'), RelatedPage::TYPE_ARTICLE);
        RelatedPage::addRelated($page, Input::get('relatedquestions'), RelatedPage::TYPE_QUESTION);

		// добавление в меню
		Menu::inMenu($page);

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
        if($page->type != Page::TYPE_SYSTEM_PAGE && $page->type != Page::TYPE_JOURNAL && $page->type != Page::TYPE_QUESTIONS) {
	        if($page->is_published) {
		        if (Page::TYPE_QUESTION == $page->type) {
			        $page->user->setNotification(Notification::TYPE_QUESTION_DELETED, [
				        '[pageTitle]' => $page->getTitle(),
			        ]);
			        $page->user->removePoints(User::POINTS_FOR_QUESTION);
		        } elseif (Page::TYPE_ARTICLE == $page->type) {
			        $page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_REMOVED, [
				        '[pageTitle]' => $page->getTitle(),
			        ]);
			        $page->user->removePoints(User::POINTS_FOR_ARTICLE);
		        }
	        }
            $page->delete();
        }

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.pages.index');
        return Redirect::to($backUrl);
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

<?php

class SidebarWidget
{
	public function show($type, $limit)
	{
		switch ($type) {
			case (Advertising::WIDGET_LATEST):
				return $this->latest($limit);
				break;
			case (Advertising::WIDGET_BEST):
				return $this->best($limit);
				break;
            case (Advertising::WIDGET_NOT_BEST):
                return $this->notBest($limit);
                break;
			case (Advertising::WIDGET_POPULAR):
				return $this->popular($limit);
				break;
			case (Advertising::WIDGET_UNPOPULAR):
				return $this->unpopular($limit);
				break;
			case (Advertising::WIDGET_COMMENTS):
				return $this->comments($limit);
				break;
			case (Advertising::WIDGET_QUESTIONS):
				return $this->questions($limit);
				break;
			case (Advertising::WIDGET_ANSWERS):
				return $this->answers($limit);
				break;
			case (Advertising::WIDGET_TAGS):
				return $this->tags($limit);
				break;
		}
	}

	/**
	 * Самое новое (по дате публикации)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function latest($limit = 7)
	{
		$cache = Cache::has('widgets.latest') ? Cache::get('widgets.latest') : [];

		if(isset($cache[$limit])) {
			return $cache[$limit];
		}

		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->where('type', '!=', Page::TYPE_QUESTION)
			->orderBy('published_at', 'DESC')
			->limit($limit)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias');
				},
			])
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title']);

		$view = (string) View::make('widgets.sidebar.latest', compact('pages'))->render();
		$cache[$limit] = $view;
		Cache::put('widgets.latest.' . $limit, $cache, 60);
		return $view;
	}

	/**
	 * Лучшие по голосам
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function best($limit = 10)
	{
		$cache = Cache::has('widgets.best') ? Cache::get('widgets.best') : [];

		if(isset($cache[$limit])) {
			$pages = $cache[$limit];
		}

		$pages = Page::select([DB::raw('id, parent_id, published_at, is_published, title, alias, votes, voters, (votes/voters) AS rating')])
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('rating', 'DESC')
			->limit($limit)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias');
				},
			])
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'votes', 'voters']);

		$cache[$limit] = $pages;
		Cache::put('widgets.best.' . $limit, $cache, 60);

		return (string) View::make('widgets.sidebar.best', compact('pages'))->render();
	}

    /**
     * Худшие по голосам
     *
     * @param int $limit Количество записей
     * @return string
     */
    public function notBest($limit = 10)
    {
	    $cache = Cache::has('widgets.notBest') ? Cache::get('widgets.notBest') : [];

	    if(isset($cache[$limit])) {
		    $pages = $cache[$limit];
	    }

	    $pages = Page::select([DB::raw('id, parent_id, published_at, is_published, title, alias, votes, voters, (votes/voters) AS rating')])
		    ->whereIsPublished(1)
		    ->where('published_at', '<', date('Y-m-d H:i:s'))
		    ->whereIsContainer(0)
		    ->where('parent_id', '!=', 0)
		    ->orderBy('rating', 'ASC')
		    ->limit($limit)
		    ->with([
			    'parent' => function($query) {
				    $query->select('id', 'type', 'alias', 'is_container', 'parent_id');
			    },
			    'parent.parent' => function($query) {
				    $query->select('id', 'type', 'alias', 'is_container', 'parent_id');
			    },
			    'user' => function($query) {
				    $query->select('id', 'login', 'alias');
			    },
		    ])
		    ->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'votes', 'voters']);

	    $cache[$limit] = $pages;
	    Cache::put('widgets.notBest.' . $limit, $cache, 60);

        return (string) View::make('widgets.sidebar.notBest', compact('pages'))->render();
    }

	/**
	 * Самое популярное (по просмотрам)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function popular($limit = 5)
	{
		$cache = Cache::has('widgets.popular') ? Cache::get('widgets.popular') : [];

		if(isset($cache[$limit])) {
			return $cache[$limit];
		}

		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('views', 'DESC')
			->limit($limit)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias');
				},
			])
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'views', 'image', 'image_alt']);

		$view = (string) View::make('widgets.sidebar.popular', compact('pages'))->render();
		$cache[$limit] = $view;
		Cache::put('widgets.popular.' . $limit, $cache, 60);
		return $view;
	}

	/**
	 * Аутсайдеры (последние по просмотрам)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function unpopular($limit = 7)
	{
		$cache = Cache::has('widgets.unpopular') ? Cache::get('widgets.unpopular') : [];

		if(isset($cache[$limit])) {
			return $cache[$limit];
		}

		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('views', 'ASC')
			->limit($limit)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias');
				},
			])
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'views', 'image', 'image_alt']);

		$view = (string) View::make('widgets.sidebar.unpopular', compact('pages'))->render();
		$cache[$limit] = $view;
		Cache::put('widgets.unpopular.' . $limit, $cache, 60);
		return $view;
	}

	/**
	 * Комментарии (последние комментарии)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function comments($limit = 9)
	{
		$cache = Cache::has('widgets.comments') ? Cache::get('widgets.comments') : [];

		if(isset($cache[$limit])) {
			return $cache[$limit];
		}

		$comments = Comment::whereIsPublished(1)
			->whereIsDeleted(0)
			->whereIsAnswer(0)
			->limit($limit)
			->with([
				'page' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'user_id');
				},
				'page.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'page.parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'page.user' => function($query) {
					$query->select('id', 'login', 'alias');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
			])
			->orderBy('created_at', 'DESC')
			->get(['id', 'parent_id', 'page_id', 'user_id', 'user_name', 'created_at', 'is_published', 'comment']);

		$view = (string) View::make('widgets.sidebar.comments', compact('comments'))->render();
		$cache[$limit] = $view;
		Cache::put('widgets.comments.' . $limit, $cache, Config::get('settings.userActivityTime'));
		return $view;
	}

	/**
	 * Ответы (лучшие ответы)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function answers($limit = 9)
	{
		$cache = Cache::has('widgets.answers') ? Cache::get('widgets.answers') : [];

		if(isset($cache[$limit])) {
			return $cache[$limit];
		}

		$answers = Comment::whereIsPublished(1)
			->whereIsDeleted(0)
			->whereIsAnswer(1)
			->whereMark(Comment::MARK_BEST)
			->limit($limit)
			->with([
				'page' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'user_id');
				},
				'page.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'page.parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
			])
			->orderBy('updated_at', 'DESC')
			->get(['id', 'parent_id', 'page_id', 'mark', 'is_answer', 'user_id', 'user_name', 'created_at', 'is_published', 'comment']);

		$view = (string) View::make('widgets.sidebar.answers', compact('answers'))->render();
		$cache[$limit] = $view;
		Cache::put('widgets.answers.' . $limit, $cache, Config::get('settings.userActivityTime'));
		return $view;
	}

	/**
	 * Вопросы пользователей (новые вопросы)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function questions($limit = 3)
	{
		$cache = Cache::has('widgets.questions') ? Cache::get('widgets.questions') : [];

		if(isset($cache[$limit])) {
			return $cache[$limit];
		}

		$questions = Page::whereType(Page::TYPE_QUESTION)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->limit($limit)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedAnswers' => function($query) {
					$query->select('id', 'page_id');
				},
				'bestComments' => function($query) {
					$query->select('id', 'page_id');
				},
			])
			->orderBy('published_at', 'DESC')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'is_container', 'alias', 'title']);

		$view = (string) View::make('widgets.sidebar.questions', compact('questions'))->render();

		$cache[$limit] = $view;
		Cache::put('widgets.questions', $cache, Config::get('settings.userActivityTime'));
		return $view;
	}

	/**
	 * Теги
	 *
	 * @param int $limit Количество тегов
	 * @return string
	 */
	public function tags($limit = 20)
	{
		$cache = Cache::has('widgets.tags') ? Cache::get('widgets.tags') : [];

		if(isset($cache[$limit])) {
			return $cache[$limit];
		}

		$tags = Tag::select('id', 'title')
			->has('pages')
			->with([
				'pages' => function($query) {
					$query->select('id');
				}
			])
			->whereHas('pages', function($query) {
				$query->whereIsPublished(1)->where('published_at', '<', date('Y-m-d H:i:s'));
			})
			->limit($limit)
			->get()
			->sortBy(function($tag) {
				return $tag->pages->count();
			})->reverse();

		$view = (string) View::make('widgets.sidebar.tags', compact('tags'))->render();

		$cache[$limit] = $view;
		Cache::forever('widgets.tags.' . $limit, $cache);
		return $view;
	}

	/**
	 * Подменю
	 *
	 * @param int $page Текущая страница
	 * @return string
	 */
	public function submenu($page)
	{
		if($page->is_container) {
			if(Cache::has('widgets.sidebar.' . $page->id)) {
				$items = Cache::get('widgets.sidebar.' . $page->id);
			} else {
				$items = Page::select(DB::raw('pages.id, pages.alias, pages.title, menus.menu_title, menus.position, pages.is_published, pages.published_at, pages.parent_id, pages.is_container, count(children.id) as pagesCount'))
					->where('pages.parent_id', '=', $page->id)
					->where('pages.is_container', '=', 1)
					->where('pages.is_published', '=', 1)
					->where('pages.published_at', '<', date('Y-m-d H:i:s'))
					->with('parent')
					->join('menus', 'pages.id', '=', 'menus.page_id')
					->leftJoin(DB::raw('pages children'), 'pages.id', '=', 'children.parent_id')
//					->where('children.is_published', '=', 1)
//					->where('children.published_at', '<', date('Y-m-d H:i:s'))
					->groupBy('pages.id')
					->orderBy('menus.position', 'ASC')
					->orderBy('pages.id', 'ASC')
					->get();

				Cache::forever('widgets.sidebar.' . $page->id, $items);
			}

			return (string) View::make('widgets.sidebar.submenu', compact('items', 'page'))->render();
		}
	}

	/**
	 * Добавление в закладки браузера
	 *
	 * @return string
	 */
	public function addToFavorites()
	{
		if(Cache::has('widgets.addToFavorites')) {
			return Cache::get('widgets.addToFavorites');
		} else {
			$view = (string) View::make('widgets.sidebar.addToFavorites')->render();

			Cache::put('widgets.addToFavorites', $view, 60*24);
			return $view;
		}
	}

	/**
	 * RSS лента
	 *
	 * @return string
	 */
	public function rss()
	{
		if(Cache::has('widgets.rss')) {
			return Cache::get('widgets.rss');
		} else {
			$view = (string) View::make('widgets.sidebar.rss')->render();

			Cache::put('widgets.rss', $view, 60*24);
			return $view;
		}
	}

	/**
	 * Социальные закладки
	 *
	 * @return string
	 */
	public function socialButtons()
	{
		if(Cache::has('widgets.socialButtons')) {
			return Cache::get('widgets.socialButtons');
		} else {
			$view = (string) View::make('widgets.sidebar.socialButtons')->render();

			Cache::put('widgets.socialButtons', $view, 60);
			return $view;
		}
	}
}
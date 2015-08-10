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
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->where('type', '!=', Page::TYPE_QUESTION)
			->orderBy('published_at', 'DESC')
			->limit($limit)
			->with('parent.parent', 'user')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'menu_title']);

		return (string) View::make('widgets.sidebar.latest', compact('pages'))->render();
	}

	/**
	 * TOP- 10 (рейтинг голосов)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function best($limit = 10)
	{
		$pages = Page::select([DB::raw('id, parent_id, published_at, is_published, title, menu_title, alias, votes, voters, (votes/voters) AS rating')])
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('rating', 'DESC')
			->limit($limit)
			->with('parent.parent', 'user')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'votes', 'voters']);

		return (string) View::make('widgets.sidebar.best', compact('pages'))->render();
	}

	/**
	 * Самое популярное (по просмотрам)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function popular($limit = 5)
	{
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('views', 'DESC')
			->limit($limit)
			->with('parent.parent', 'user')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'views', 'image', 'image_alt']);

		return (string) View::make('widgets.sidebar.popular', compact('pages'))->render();
	}

	/**
	 * Аутсайдеры (последние по просмотрам)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function unpopular($limit = 7)
	{
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('views', 'ASC')
			->limit($limit)
			->with('parent.parent', 'user')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'views', 'image', 'image_alt']);

		return (string) View::make('widgets.sidebar.unpopular', compact('pages'))->render();
	}

	/**
	 * Комментарии (последние комментарии)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function comments($limit = 9)
	{
		$comments = Comment::whereIsPublished(1)
			->whereIsAnswer(0)
			->limit($limit)
			->with('page.parent.parent', 'user')
			->orderBy('created_at', 'DESC')
			->get(['id', 'parent_id', 'page_id', 'user_id', 'created_at', 'is_published', 'comment']);

		return (string) View::make('widgets.sidebar.comments', compact('comments'))->render();
	}

	/**
	 * Ответы (лучшие ответы)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function answers($limit = 9)
	{
		$comments = Comment::whereIsPublished(1)
			->whereIsAnswer(1)
			->whereMark(Comment::MARK_BEST)
			->limit($limit)
			->with('page.parent.parent', 'user')
			->orderBy('updated_at', 'DESC')
			->get(['id', 'parent_id', 'page_id', 'user_id', 'created_at', 'is_published', 'comment']);

		return (string) View::make('widgets.sidebar.comments', compact('comments'))->render();
	}

	/**
	 * Вопросы пользователей (новые вопросы)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function questions($limit = 3)
	{
		$questions = Page::whereType(Page::TYPE_QUESTION)
		    ->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->limit($limit)
			->with('parent.parent', 'user', 'publishedAnswers', 'bestComments')
			->orderBy('created_at', 'DESC')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'is_container', 'alias', 'title', 'menu_title']);

		return (string) View::make('widgets.sidebar.questions', compact('questions'))->render();
	}

	/**
	 * Вопросы пользователей (новые вопросы)
	 *
	 * @param int $limit Количество записей
	 * @return string
	 */
	public function tags($limit = 20)
	{
		$tags = Tag::has('pages')
			->with('pages')
			->limit($limit)
			->get()
			->sortBy(function($tag) {
					return $tag->pages->count();
			})->reverse();

		return (string) View::make('widgets.sidebar.tags', compact('tags'))->render();
	}

	/**
	 * Добавление в закладки браузера
	 *
	 * @return string
	 */
	public function addToFavorites() {
		return (string) View::make('widgets.sidebar.addToFavorites')->render();
	}

	/**
	 * RSS лента
	 *
	 * @return string
	 */
	public function rss() {
		return (string) View::make('widgets.sidebar.rss')->render();
	}

}
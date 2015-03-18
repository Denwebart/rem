<?php

class SidebarWidget
{
	/**
	 * Самое новое (по дате публикации)
	 *
	 * @param int $limit Количество записей
	 * @return array
	 */
	public function latest($limit = 7)
	{
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIn('parent_id', [5, 6])
			->orderBy('published_at', 'DESC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title']);

		return (string) View::make('widgets.sidebar.latest', compact('pages'))->render();
	}

	/**
	 * TOP- 10 (рейтинг голосов)
	 *
	 * @param int $limit Количество записей
	 * @return array
	 */
	public function best($limit = 10)
	{
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIn('parent_id', [5, 6])
			->orderBy('votes', 'DESC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title', 'votes', 'voters']);

		return (string) View::make('widgets.sidebar.best', compact('pages'))->render();
	}

	/**
	 * Самое популярное (по просмотрам)
	 *
	 * @param int $limit Количество записей
	 * @return array
	 */
	public function popular($limit = 6)
	{
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIn('parent_id', [5, 6])
			->orderBy('views', 'DESC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title', 'views']);

		return (string) View::make('widgets.sidebar.popular', compact('pages'))->render();
	}

	/**
	 * Аутсайдеры (последние по просмотрам)
	 *
	 * @param int $limit Количество записей
	 * @return array
	 */
	public function unpopular($limit = 7)
	{
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIn('parent_id', [5, 6])
			->orderBy('views', 'ASC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title', 'views']);

		return (string) View::make('widgets.sidebar.unpopular', compact('pages'))->render();
	}

	/**
	 * Комментарии (последние комментарии)
	 *
	 * @param int $limit Количество записей
	 * @return array
	 */
	public function comments($limit = 9)
	{
		$comments = Comment::whereIsPublished(1)
			->limit($limit)
			->with(['parent', 'user'])
			->orderBy('created_at', 'DESC')
			->get(['id', 'parent_id', 'page_id', 'user_id', 'created_at', 'is_published', 'comment']);

		return (string) View::make('widgets.sidebar.comments', compact('comments'))->render();
	}

}
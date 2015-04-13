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
			->whereIn('parent_id', [14, 16, 20, 21, 22, 23, 36])
			->orderBy('published_at', 'DESC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title', 'menu_title']);

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
		$pages = Page::select([DB::raw('title, menu_title, alias, votes, voters, (votes/voters) AS rating')])
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIn('parent_id', [14, 16, 20, 21, 22, 23, 36])
			->orderBy('rating', 'DESC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'votes', 'voters']);

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
			->whereIn('parent_id', [14, 16, 20, 21, 22, 23, 36])
			->orderBy('views', 'DESC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'views']);

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
			->whereIn('parent_id', [14, 16, 20, 21, 22, 23, 36])
			->orderBy('views', 'ASC')
			->limit($limit)
			->with(['parent'])
			->get(['id', 'parent_id', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'views']);

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

	/**
	 * Добавление в закладки браузера
	 *
	 * @return array
	 */
	public function addToFavorites() {
		return (string) View::make('widgets.sidebar.addToFavorites')->render();
	}

}
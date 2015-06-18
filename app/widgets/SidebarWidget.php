<?php

class SidebarWidget
{
	private $settings = [];

	public function __construct() {
		$settings = Setting::whereCategory('SidebarWidget')->get();
		foreach($settings as $setting) {
			$this->settings[$setting->key] = $setting->value;
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
		$limit = ($this->settings['countOfLatest']) ? $this->settings['countOfLatest'] : $limit;
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
		$limit = ($this->settings['countOfBest']) ? $this->settings['countOfBest'] : $limit;
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
		$limit = ($this->settings['countOfPopular']) ? $this->settings['countOfPopular'] : $limit;
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('views', 'DESC')
			->limit($limit)
			->with('parent.parent', 'user')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'views']);

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
		$limit = ($this->settings['countOfUnpopular']) ? $this->settings['countOfUnpopular'] : $limit;
		$pages = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->orderBy('views', 'ASC')
			->limit($limit)
			->with('parent.parent', 'user')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'alias', 'title', 'menu_title', 'views']);

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
		$limit = ($this->settings['countOfComments']) ? $this->settings['countOfComments'] : $limit;
		$comments = Comment::whereIsPublished(1)
			->limit($limit)
			->with('page.parent.parent', 'user')
			->orderBy('created_at', 'DESC')
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
		$limit = ($this->settings['countOfQuestions']) ? $this->settings['countOfQuestions'] : $limit;
		$questions = Page::whereType(Page::TYPE_QUESTION)
		    ->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->limit($limit)
			->with('parent.parent', 'user', 'comments')
			->orderBy('created_at', 'DESC')
			->get(['id', 'parent_id', 'user_id', 'type', 'published_at', 'is_published', 'is_container', 'alias', 'title', 'menu_title']);

		return (string) View::make('widgets.sidebar.questions', compact('questions'))->render();
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
<?php

/**
 * Class CommentWidget
 *
 * Виджет для вывода комментариев (2 уровня).
 * Лучшие комментарии, голосование за комментарий.
 * Ответ на комментарий для зарегистрированных и незарегистрированных (с капчей) пользователей.
 *
 */
class CommentWidget
{
	public $title = 'Комментарии';
	public $formTitle = 'Оставить комментарий';

	public function show($page)
	{
		$query = new Comment();
		$query = $this->getCriteria($page, $query);
		$query = $query->whereMark(0);
		$comments = $query->paginate(Config::get('settings.commentsCountOnPage', 10));

		if(Page::TYPE_QUESTION == $page->type) {
			$query = new Comment();
			$query = $this->getCriteria($page, $query);
			$query = $query->whereMark(Comment::MARK_BEST);
			$bestComments = $query->get();
		} else {
			$bestComments = [];
		}

		return (string) View::make('widgets.comment.commentsTree', compact('comments', 'bestComments', 'page'))
			->with('title', $this->title)
			->with('formTitle', $this->formTitle)
			->with('isBannedIp', Ip::isBanned())
			->render();
	}

	/**
	 * Условие для выбора комментариев
	 *
	 * @param $page
	 * @param $query
	 * @return mixed
	 */
	protected function getCriteria($page, $query)
	{
		return $query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
			->whereIsPublished(1)
			->whereParentId(0)
			->wherePageId($page->id)
			->orderBy('created_at', 'DESC')
			->with([
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
				'publishedChildren' => function($query) {
					$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
				},
				'publishedChildren.user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
			]);
	}

}
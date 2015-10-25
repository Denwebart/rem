<?php

class CommentWidget
{
	public $title = 'Комментарии';
	public $formTitle = 'Оставить комментарий';

	public function show($page)
	{
		if(Page::TYPE_QUESTION == $page->type) {
			$comments = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
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
				])
				->whereMark(0)
				->paginate(20);

			$bestComments = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
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
				])
				->whereMark(Comment::MARK_BEST)
				->paginate(20);
		} else {
			$comments = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
				->whereIsPublished(1)
				->whereParentId(0)
				->wherePageId($page->id)
				->orderBy('created_at', 'DESC')
				->with(['user', 'publishedChildren.user'])
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
				])
				->paginate(20);
			$bestComments = [];
		}

		return (string) View::make('widgets.comment.commentsTree', compact('comments', 'bestComments', 'page'))
			->with('title', $this->title)
			->with('formTitle', $this->formTitle)
			->with('isBannedIp', Ip::isBanned())
			->render();
	}

}
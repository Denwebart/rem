<?php

class CommentWidget
{
	public $title = 'Комментарии';
	public $formTitle = 'Оставить комментарий';
	public $successMessage = 'Ваш комментарий успешно отправлен!';
	public $successMessageIfNotAuth = 'Ваш комментарий отправлен и будет опубликован после проверки модератором.';

	public function show($page)
	{
		if(Page::TYPE_QUESTION == $page->type) {
			$comments = Comment::whereIsPublished(1)
				->whereParentId(0)
				->wherePageId($page->id)
				->orderBy('created_at', 'DESC')
				->with(['user', 'publishedChildren.user'])
				->whereMark(0)
				->paginate(20);

			$bestComments = Comment::whereIsPublished(1)
				->whereParentId(0)
				->wherePageId($page->id)
				->orderBy('created_at', 'DESC')
				->with(['user', 'publishedChildren.user'])
				->whereMark(Comment::MARK_BEST)
				->paginate(20);
		} else {
			$comments = Comment::whereIsPublished(1)
				->whereParentId(0)
				->wherePageId($page->id)
				->orderBy('created_at', 'DESC')
				->with(['user', 'publishedChildren.user'])
				->paginate(20);
			$bestComments = [];
		}

		return (string) View::make('widgets.comment.commentsTree', compact('comments', 'bestComments', 'page'))
			->with('title', $this->title)
			->with('formTitle', $this->formTitle)
			->with('successMessage', Auth::check() ? $this->successMessage : $this->successMessageIfNotAuth)
			->with('isBannedIp', Ip::isBanned())
			->render();
	}

}
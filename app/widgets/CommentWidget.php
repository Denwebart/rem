<?php

class CommentWidget
{
	public $title = 'Комментарии';
	public $formTitle = 'Оставить комментарий';
	public $successMessage = 'Ваш комментарий успешно отправлен!';

	public function show($page)
	{
		$comments = Comment::whereIsPublished(1)
			->whereParentId(0)
			->wherePageId($page->id)
			->orderBy('created_at')
			->with(['user'])
			->get();

		return (string) View::make('widgets.comment.commentsTree', compact('comments', 'page'))
			->with('title', $this->title)
			->with('formTitle', $this->formTitle)
			->with('successMessage', $this->successMessage)
			->render();
	}

}
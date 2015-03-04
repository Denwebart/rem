<?php

class CommentWidget
{

	public function show($page)
	{
		$comments = Comment::whereIsPublished(1)
			->whereParentId(0)
			->wherePageId($page->id)
			->orderBy('created_at')
			->with(['user'])
			->get();

		return (string) View::make('widgets.comment.commentsTree', compact('comments', 'page'))->render();
	}

}
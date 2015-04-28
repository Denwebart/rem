<?php

class RelatedWidget
{
	public function show($page, $limit = 5)
	{
		$pages = Page::select([DB::raw('id, parent_id, published_at, is_published, title, alias')])
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereParentId($page->parent_id)
			->orderBy(DB::raw('RAND()'))
			->with('parent.parent')
			->limit($limit)
			->get();

		return (string) View::make('widgets.related.index', compact('pages'))->render();
	}

}
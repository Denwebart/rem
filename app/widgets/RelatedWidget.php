<?php

class RelatedWidget
{
	public function show($page, $limit = 5)
	{
		$metaKey = $page->meta_key ? str_replace(',', '|', $page->meta_key) . '|' : '';
		$keywords = $metaKey . StringHelper::autoMetaKeywords($page->title . ' ' . $page->content, 5, '|');

		$pages0 = $page->relatedArticles;

		$pages1 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_PAGE)
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->whereRaw('LOWER(title) LIKE LOWER("%'. str_replace('|', '%', $keywords) .'%")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages2 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_PAGE)
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->whereRaw('LOWER(content) LIKE LOWER("%'. str_replace('|', '%', $keywords) .'%")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);


		$pages3 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_PAGE)
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->whereRaw('LOWER(title) REGEXP LOWER("' . $keywords . '")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages4 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_PAGE)
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->whereRaw('LOWER(content) REGEXP LOWER("' . $keywords . '")')
			->with('parent.parent', 'user')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages5 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_PAGE)
			->whereIsContainer(0)
			->where('parent_id', '!=', 0)
			->whereParentId($page->parent_id)
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages = $pages0->merge($pages1);
		$pages = $pages->merge($pages2);
		$pages = $pages->merge($pages3);
		$pages = $pages->merge($pages4);
		$pages = $pages->merge($pages5);
		$pages = $pages->slice(0, $limit);

		return (string) View::make('widgets.related.index', compact('pages'))->render();
	}

	public function questions($page, $limit = 5)
	{
		$metaKey = $page->meta_key ? str_replace(',', '|', $page->meta_key) . '|' : '';
		$keywords = $metaKey . StringHelper::autoMetaKeywords($page->title . ' ' . $page->content, 5, '|');

		$pages0 = $page->relatedQuestions;

		$pages1 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_QUESTION)
			->whereRaw('LOWER(title) LIKE LOWER("%'. str_replace('|', '%', $keywords) .'%")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages2 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_QUESTION)
			->whereRaw('LOWER(content) LIKE LOWER("%'. str_replace('|', '%', $keywords) .'%")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);


		$pages3 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_QUESTION)
			->whereRaw('LOWER(title) REGEXP LOWER("' . $keywords . '")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages4 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_QUESTION)
			->whereRaw('LOWER(content) REGEXP LOWER("' . $keywords . '")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages5 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereType(Page::TYPE_QUESTION)
			->whereParentId($page->parent_id)
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
		
		$pages = $pages0->merge($pages1);
		$pages = $pages->merge($pages2);
		$pages = $pages->merge($pages3);
		$pages = $pages->merge($pages4);
		$pages = $pages->merge($pages5);
		$pages = $pages->slice(0, $limit);

		return (string) View::make('widgets.related.questions', compact('pages'))->render();
	}

	public function articles($page, $limit = 5)
	{
		$metaKey = $page->meta_key ? str_replace(',', '|', $page->meta_key) . '|' : '';
		$keywords = $metaKey . StringHelper::autoMetaKeywords($page->title . ' ' . $page->content, 5, '|');

		$pages0 = $page->relatedArticles;

		$pages1 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->where(function ($q) {
				$q->whereType(Page::TYPE_ARTICLE)
					->orWhere(function ($query) {
						$query->where('type', '=', Page::TYPE_PAGE)
							->whereIsContainer(0)
							->where('parent_id', '!=', 0);
					});
			})
			->whereRaw('LOWER(title) LIKE LOWER("%'. str_replace('|', '%', $keywords) .'%")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages2 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->where(function ($q) {
				$q->whereType(Page::TYPE_ARTICLE)
					->orWhere(function ($query) {
						$query->where('type', '=', Page::TYPE_PAGE)
							->whereIsContainer(0)
							->where('parent_id', '!=', 0);
					});
			})
			->whereRaw('LOWER(content) LIKE LOWER("%'. str_replace('|', '%', $keywords) .'%")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);


		$pages3 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->where(function ($q) {
				$q->whereType(Page::TYPE_ARTICLE)
					->orWhere(function ($query) {
						$query->where('type', '=', Page::TYPE_PAGE)
							->whereIsContainer(0)
							->where('parent_id', '!=', 0);
					});
			})
			->whereRaw('LOWER(title) REGEXP LOWER("' . $keywords . '")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages4 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->where(function ($q) {
				$q->whereType(Page::TYPE_ARTICLE)
					->orWhere(function ($query) {
						$query->where('type', '=', Page::TYPE_PAGE)
							->whereIsContainer(0)
							->where('parent_id', '!=', 0);
					});
			})
			->whereRaw('LOWER(content) REGEXP LOWER("' . $keywords . '")')
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages5 = Page::whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->where(function ($q) {
				$q->whereType(Page::TYPE_ARTICLE)
					->orWhere(function ($query) {
						$query->where('type', '=', Page::TYPE_PAGE)
							->whereIsContainer(0)
							->where('parent_id', '!=', 0);
					});
			})
			->whereParentId($page->parent_id)
			->with('parent.parent', 'user')
			->limit($limit)
			->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);

		$pages = $pages0->merge($pages1);
		$pages = $pages->merge($pages2);
		$pages = $pages->merge($pages3);
		$pages = $pages->merge($pages4);
		$pages = $pages->merge($pages5);
		$pages = $pages->slice(0, $limit);

		return (string) View::make('widgets.related.articles', compact('pages'))->render();
	}

}
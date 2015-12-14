<?php

class RelatedWidget
{
	public function show($page, $limit = 3)
	{
		$cache = Cache::has('related.articles') ? Cache::get('related.articles') : [];

		if(isset($cache[$page->id])) {
			return $cache[$page->id];
		}

		/* установленные админом */
		$pages = $page->relatedArticles;

		if(count($pages) < $limit) {
			if($page->pagesTags) {
				/* по тегам в этой-же категории */
				$query = new Page;
				$query = $this->getCriteria($page, $query, $limit);
				$query = $query->where(function($q) {
					$q->where('type', '=', Page::TYPE_PAGE)
						->orWhere('type', '=', Page::TYPE_ARTICLE);
				});
				$query = $query->where('parent_id', '=', $page->parent_id);
				$query = $query->whereHas('pagesTags', function($q) use($page) {
					return $q->whereIn('tag_id', $page->pagesTags->lists('tag_id', 'tag_id'));
				});
				$query = $query->limit($limit - count($pages));
				$pagesWithTagsInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
				$pages = $pages->merge($pagesWithTagsInCategory);

				if(count($pages) < $limit) {
					/* по тегам на всем сайте */
					$query = new Page;
					$query = $this->getCriteria($page, $query, $limit);
					$query = $query->where(function($q) {
						$q->where('type', '=', Page::TYPE_PAGE)
							->orWhere('type', '=', Page::TYPE_ARTICLE);
					});
					$query = $query->whereHas('pagesTags', function($q) use($page) {
						$q->whereIn('tag_id', $page->pagesTags->lists('tag_id', 'tag_id'));
					});
					$query = $query->limit($limit - count($pages));
					$pagesWithTags = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
					$pages = $pages->merge($pagesWithTags);
				}
			}

			if(count($pages) < $limit) {
				/* по маркам машин (цифрам) в заголовках */

				$carModel = $this->getCarModels($page);

				if(count($carModel)) {
					$query = new Page;
					$query = $this->getCriteria($page, $query, $limit);
					$query = $query->where(function($q) {
						$q->where('type', '=', Page::TYPE_PAGE)
							->orWhere('type', '=', Page::TYPE_ARTICLE);
					});
					$query = $query->whereRaw('LOWER(title) LIKE LOWER("%'. implode('%', $carModel) .'%")');
					$query = $query->orderBy(DB::raw('RAND()'));
					$query = $query->limit($limit - count($pages));
					$pagesInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
					$pages = $pages->merge($pagesInCategory);
				}
			}

			if(count($pages) < $limit) {
				/* рандомно в категории */
				$query = new Page;
				$query = $this->getCriteria($page, $query, $limit);
				$query = $query->where(function($q) {
					$q->where('type', '=', Page::TYPE_PAGE)
						->orWhere('type', '=', Page::TYPE_ARTICLE);
				});
				$query = $query->where('parent_id', '=', $page->parent_id);
				$query = $query->orderBy(DB::raw('RAND()'));
				$query = $query->limit($limit - count($pages));
				$pagesInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
				$pages = $pages->merge($pagesInCategory);
			}
		}

		$view = (string) View::make('widgets.related.index', compact('pages'))->render();
		$cache[$page->id] = $view;
		Cache::put('related.articles', $cache, 60 * 12);
		return $view;
	}

	public function questions($page, $limit = 3)
	{
		$cache = Cache::has('related.questions') ? Cache::get('related.questions') : [];

		if(isset($cache[$page->id])) {
			return $cache[$page->id];
		}

		/* установленные админом */
		$pages = $page->relatedQuestions;

		if(count($pages) < $limit) {
			/* по маркам машин (цифрам) в заголовках */

			$carModel = $this->getCarModels($page);

			if(count($carModel)) {
				$query = new Page;
				$query = $this->getCriteria($page, $query, $limit);
				$query = $query->whereType(Page::TYPE_QUESTION);
				$query = $query->whereRaw('LOWER(title) LIKE LOWER("%'. implode('%', $carModel) .'%")');
				$query = $query->orderBy(DB::raw('RAND()'));
				$query = $query->limit($limit - count($pages));
				$pagesInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
				$pages = $pages->merge($pagesInCategory);
			}
		}

		if(count($pages) < $limit) {
			if(count($pages) < $limit) {
				/* рандомно в категории */
				$query = new Page;
				$query = $this->getCriteria($page, $query, $limit);
				$query = $query->whereType(Page::TYPE_QUESTION);
				$query = $query->where('parent_id', '=', $page->parent_id);
				$query = $query->orderBy(DB::raw('RAND()'));
				$query = $query->limit($limit - count($pages));
				$pagesInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
				$pages = $pages->merge($pagesInCategory);
			}
		}

		$view = (string) View::make('widgets.related.questions', compact('pages'))->render();
		$cache[$page->id] = $view;
		Cache::put('related.questions', $cache, 60 * 12);
		return $view;
	}

	public function articles($page, $limit = 3)
	{
		$cache = Cache::has('related.articles') ? Cache::get('related.articles') : [];

		if(isset($cache[$page->id])) {
			return $cache[$page->id];
		}

		/* установленные админом */
		$pages = $page->relatedArticles;

		if(count($pages) < $limit) {
			if($page->pagesTags) {
				/* по тегам в этой-же категории */
				$query = new Page;
				$query = $this->getCriteria($page, $query, $limit);
				$query = $query->where(function($q) {
					$q->where('type', '=', Page::TYPE_PAGE)
						->orWhere('type', '=', Page::TYPE_ARTICLE);
				});
				$query = $query->where('parent_id', '=', $page->parent_id);
				$query = $query->whereHas('pagesTags', function($q) use($page) {
					return $q->whereIn('tag_id', $page->pagesTags->lists('tag_id', 'tag_id'));
				});
				$query = $query->limit($limit - count($pages));
				$pagesWithTagsInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
				$pages = $pages->merge($pagesWithTagsInCategory);

				if(count($pages) < $limit) {
					/* по тегам на всем сайте */
					$query = new Page;
					$query = $this->getCriteria($page, $query, $limit);
					$query = $query->where(function($q) {
						$q->where('type', '=', Page::TYPE_PAGE)
							->orWhere('type', '=', Page::TYPE_ARTICLE);
					});
					$query = $query->whereHas('pagesTags', function($q) use($page) {
						$q->whereIn('tag_id', $page->pagesTags->lists('tag_id', 'tag_id'));
					});
					$query = $query->limit($limit - count($pages));
					$pagesWithTags = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
					$pages = $pages->merge($pagesWithTags);
				}
			}

			if(count($pages) < $limit) {
				/* по маркам машин (цифрам) в заголовках */

				$carModel = $this->getCarModels($page);

				if(count($carModel)) {
					$query = new Page;
					$query = $this->getCriteria($page, $query, $limit);
					$query = $query->where(function($q) {
						$q->where('type', '=', Page::TYPE_PAGE)
							->orWhere('type', '=', Page::TYPE_ARTICLE);
					});
					$query = $query->whereRaw('LOWER(title) LIKE LOWER("%'. implode('%', $carModel) .'%")');
					$query = $query->orderBy(DB::raw('RAND()'));
					$query = $query->limit($limit - count($pages));
					$pagesInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
					$pages = $pages->merge($pagesInCategory);
				}
			}

			if(count($pages) < $limit) {
				/* рандомно в категории */
				$query = new Page;
				$query = $this->getCriteria($page, $query, $limit);
				$query = $query->where(function($q) {
					$q->where('type', '=', Page::TYPE_PAGE)
						->orWhere('type', '=', Page::TYPE_ARTICLE);
				});
				$query = $query->where('parent_id', '=', $page->parent_id);
				$query = $query->orderBy(DB::raw('RAND()'));
				$query = $query->limit($limit - count($pages));
				$pagesInCategory = $query->get(['id', 'parent_id', 'published_at', 'user_id', 'is_published', 'is_container', 'title', 'alias', 'type']);
				$pages = $pages->merge($pagesInCategory);
			}
		}

		$view = (string) View::make('widgets.related.articles', compact('pages'))->render();
		$cache[$page->id] = $view;
		Cache::put('related.articles', $cache, 60 * 12);
		return $view;
	}

	protected function getKeywords($page)
	{
		$metaKey = $page->meta_key ? str_replace(',', '|', $page->meta_key) . '|' : '';
		$keywords = $metaKey . StringHelper::autoMetaKeywords($page->title . ' ' . $page->content, 5, '|');
		return preg_replace('/\|{2,}/','|', $keywords);
	}

	protected function getCarModels($page)
	{
		preg_match_all('/[0-9]{1,9}/', $page->title, $array);
		$result = [];
		foreach($array[0] as $key => $item) {
			$result[$key] = str_replace('-', '', $item);
		}
		if(!count($result)) {
			preg_match_all('/[0-9]{1,9}/', $page->content, $array);
			foreach($array[0] as $key => $item) {
				$result[$key] = str_replace('-', '', $item);
			}
		}
		return $result;
	}

	protected function getCriteria($page, $query, $limit)
	{
		return $query->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->where('id', '!=', $page->id)
			->whereIsContainer(0)
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
			])
			->where('parent_id', '!=', 0);
	}
}
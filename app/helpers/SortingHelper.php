<?php


class SortingHelper {

	public static function sortingLink($route, $title, $column)
	{
		$direction = (Request::get('direction') == 'asc') ? 'desc' : 'asc';
		return link_to_route($route, $title, ['sortBy' => $column, 'direction' => $direction]);
	}

	public static function paginationLinks($pages)
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if($sortBy && $direction) {
			return $pages->appends(['sortBy' => $sortBy, 'direction' => $direction])->links();
		}
		return $pages->links();
	}

}
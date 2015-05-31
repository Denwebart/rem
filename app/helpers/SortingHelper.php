<?php


class SortingHelper {

	public static function sortingLink($route, $title, $column, $params = [])
	{
		$direction = (Request::get('direction') == 'asc') ? 'desc' : 'asc';
		$icon = Request::get('sortBy') == $column ? self::getIcon($direction) : '';
		return HTML::decode(link_to_route(
			$route,
			$title . $icon,
			$params + ['sortBy' => $column, 'direction' => $direction]
		));
	}

	protected static function getIcon($direction) {
		return ($direction == 'asc')
			? ' <i class="fa fa-long-arrow-down"></i>'
			: ' <i class="fa fa-long-arrow-up"></i>';
	}

	public static function paginationLinks($model)
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if($sortBy && $direction) {
			return $model->appends(['sortBy' => $sortBy, 'direction' => $direction])->links();
		}
		return $model->links();
	}

}
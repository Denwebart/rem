<?php

class AdminMenusController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of menu.
	 *
	 * @return Response
	 */
	public function index()
	{
        $menus = Menu::select(DB::raw('type, count(*) as pagesCount'))
	        ->orderBy('type', 'ASC')
            ->groupBy('type')->get();

		return View::make('admin::menus.index', compact('menus'));
	}

	/**
	 * Display a listing of menu items.
	 *
	 * @return Response
	 */
	public function items($type)
	{
		$menuItems = Menu::whereType($type)
			->whereParentId(0)
			->with('page', 'children')
			->orderBy('position', 'ASC')
			->get();

		return View::make('admin::menus.items', compact('menuItems', 'type'));
	}

	/**
	 * Изменение позиции пункта меню
	 *
	 * @param $type
	 */
	public function changePosition($type)
	{
		$positions = \Input::get('positions');
		$i = 0;

		foreach($positions as $value) {
			$menu = Menu::find($value);
			$menu->position = $i;
			$menu->save();
			$i++;
		}
	}

}

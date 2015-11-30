<?php
class MenuWidget
{
	public $topMenu;
	public $mainMenu;
	public $bottomMenu;

	public function __construct()
	{
		$this->topMenu = $this->topMenu();
		$this->mainMenu = $this->mainMenu();
		$this->bottomMenu = $this->bottomMenu();
	}

	public function topMenu()
	{
		if(Cache::has('menu.top')) {
			$items = Cache::get('menu.top');
		} else {
			$items = Page::select(DB::raw('pages.alias, pages.type, pages.parent_id, pages.is_container, pages.title, pages.menu_title, menus.position'))
				->join('menus', 'pages.id', '=', 'menus.page_id')
				->where('menus.type', '=', Menu::TYPE_TOP)
				->orderBy('menus.position', 'ASC')
				->get();

			Cache::forever('menu.top', $items);
		}
		return (string) View::make('widgets.menu.top', compact('items'))->render();
	}

	public function mainMenu()
	{
		if(Cache::has('menu.main')) {
			$items = Cache::get('menu.main');
		} else {
			$items = Menu::select('id', 'type', 'page_id', 'parent_id', 'position')
				->whereType(Menu::TYPE_MAIN)
				->whereParentId(0)
				->with([
					'page' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'children' => function($query) {
						$query->select('id', 'type', 'page_id', 'parent_id', 'position');
					},
					'children.page' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id', 'title', 'menu_title');
					},
					'children.page.parent' => function($query) {
						$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
					},
				])
				->orderBy('position', 'ASC')
				->get();

			Cache::forever('menu.main', $items);
		}
		return (string) View::make('widgets.menu.main', compact('items'))->render();
	}

	public function bottomMenu()
	{
		if(Cache::has('menu.bottom')) {
			$items = Cache::get('menu.bottom');
		} else {
			$items = Page::select(DB::raw('pages.alias, pages.type, pages.parent_id, pages.is_container, pages.title, pages.menu_title, menus.position'))
				->join('menus', 'pages.id', '=', 'menus.page_id')
				->where('menus.type', '=', Menu::TYPE_BOTTOM)
				->orderBy('menus.position', 'ASC')
				->get();

			Cache::forever('menu.bottom', $items);
		}
		return (string) View::make('widgets.menu.bottom', compact('items'))->render();
	}
}
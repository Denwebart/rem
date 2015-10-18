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
		$items = Page::select(DB::raw('pages.alias, pages.type, pages.parent_id, pages.is_container, pages.title, menus.menu_title, menus.position'))
			->join('menus', 'pages.id', '=', 'menus.page_id')
			->where('menus.type', '=', Menu::TYPE_TOP)
			->orderBy('menus.position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.top', compact('items'))->render();
	}

	public function mainMenu()
	{
		$items = Menu::select('id', 'type', 'page_id', 'parent_id', 'position', 'menu_title')
			->whereType(Menu::TYPE_MAIN)
			->whereParentId(0)
			->with([
				'page' => function($query) {
				    $query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'children' => function($query) {
					$query->select('id', 'type', 'page_id', 'parent_id', 'position', 'menu_title');
				},
				'children.page' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'children.page.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
			])
			->orderBy('position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.main', compact('items'))->render();
	}

	public function bottomMenu()
	{
		$items = Page::select(DB::raw('pages.alias, pages.type, pages.parent_id, pages.is_container, pages.title, menus.menu_title, menus.position'))
			->join('menus', 'pages.id', '=', 'menus.page_id')
			->where('menus.type', '=', Menu::TYPE_BOTTOM)
			->orderBy('menus.position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.bottom', compact('items'))->render();
	}
}
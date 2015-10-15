<?php
class MenuWidget
{

	public function topMenu()
	{
//		$pages = Page::whereIn('id', [10, 11, 14])
//			->where('is_published', 1)
//			->get(['id', 'is_published', 'alias', 'type', 'is_container', 'menu_title', 'title']);
		$items = Menu::whereType(Menu::TYPE_TOP)
			->with('page')
			->orderBy('position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.top', compact('items'))->render();
	}

	public function mainMenu()
	{
//		$pages = Page::whereParentId(0)
//			->whereNotIn('id', [10, 11, 14, 13])
//			->where('is_published', 1)
//			->with('publishedChildren.parent')
//			->get(['id', 'is_published', 'alias', 'type', 'is_container', 'menu_title', 'title', 'show_submenu']);

		$items = Menu::whereType(Menu::TYPE_MAIN)
			->whereParentId(0)
			->with('page', 'children.page')
			->orderBy('position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.main', compact('items'))->render();
	}

	public function bottomMenu()
	{
//		$pages = Page::whereParentId(0)
//			->whereNotIn('id', [14, 13])
//			->where('is_published', 1)
//			->get(['id', 'is_published', 'alias', 'type', 'is_container', 'menu_title', 'title']);

		$items = Menu::whereType(Menu::TYPE_BOTTOM)
			->with('page')
			->orderBy('position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.bottom', compact('items'))->render();
	}
}
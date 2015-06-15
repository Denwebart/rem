<?php
class MenuWidget
{

	public function topMenu()
	{
		$pages = Page::whereIn('id', [10, 11, 60])
			->where('is_published', 1)
			->get(['id', 'is_published', 'alias', 'is_container', 'menu_title', 'title']);
		return (string) View::make('widgets.menu.top', compact('pages'))->render();
	}

	public function mainMenu()
	{
		$pages = Page::whereParentId(0)
			->whereNotIn('id', [10, 11, 60, 58])
			->where('is_published', 1)
			->with(['publishedChildren'])
			->get(['id', 'is_published', 'alias', 'is_container', 'menu_title', 'title', 'show_submenu']);
		return (string) View::make('widgets.menu.main', compact('pages'))->render();
	}

	public function bottomMenu()
	{
		$pages = Page::whereParentId(0)
			->whereNotIn('id', [60, 58])
			->where('is_published', 1)
			->get(['id', 'is_published', 'alias', 'is_container', 'menu_title', 'title']);
		return (string) View::make('widgets.menu.bottom', compact('pages'))->render();
	}
}
<?php
class MenuWidget
{

	public function topMenu()
	{
		$items = Menu::whereType(Menu::TYPE_TOP)
			->with('page')
			->orderBy('position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.top', compact('items'))->render();
	}

	public function mainMenu()
	{
		$items = Menu::whereType(Menu::TYPE_MAIN)
			->whereParentId(0)
			->with('page', 'children.page')
			->orderBy('position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.main', compact('items'))->render();
	}

	public function bottomMenu()
	{
		$items = Menu::whereType(Menu::TYPE_BOTTOM)
			->with('page')
			->orderBy('position', 'ASC')
			->get();

		return (string) View::make('widgets.menu.bottom', compact('items'))->render();
	}
}
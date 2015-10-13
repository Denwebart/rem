<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillMenusTable extends Migration {

	public function up()
	{
		/* Верхнее меню */
		$topMenuPages = $pages = Page::whereIn('id', [10, 11, 14])
			->where('is_published', 1)
			->get();
		foreach($topMenuPages as $item) {
			DB::table('menus')->insert(
			[
				[
					'type' => Menu::TYPE_TOP,
					'page_id' => $item->id,
					'menu_title' => $item->getTitle()
				]
			]);
		}

		/* Главное меню */
		$mainMenuPages = $pages = Page::whereParentId(0)
			->whereNotIn('id', [10, 11, 14, 13])
			->where('is_published', 1)
			->get();
		foreach($mainMenuPages as $item) {
			$menuItemId = DB::table('menus')->insertGetId(
				[
					'type' => Menu::TYPE_MAIN,
					'page_id' => $item->id,
					'menu_title' => $item->getTitle(),
					'show_submenu' => $item->show_submenu
				]
			);
			if($item->show_submenu) {
				foreach($item->publishedChildren as $itemChildren) {
					DB::table('menus')->insert([
						[
							'type' => Menu::TYPE_MAIN,
							'page_id' => $itemChildren->id,
							'parent_id' => $menuItemId,
							'menu_title' => $itemChildren->getTitle()
						]
					]);
				}
			}
		}

		/* Нижнее меню */
		$bottomMenuPages = $pages = Page::whereParentId(0)
			->whereNotIn('id', [14, 13])
			->where('is_published', 1)
			->get();
		foreach($bottomMenuPages as $item) {
			DB::table('menus')->insert([
				[
					'type' => Menu::TYPE_BOTTOM,
					'page_id' => $item->id,
					'menu_title' => $item->getTitle()
				]
			]);
		}

	}

	public function down()
	{
		DB::table('menus')->delete();
	}

}

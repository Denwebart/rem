<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillFieldsTitleIsShowTitleForPagesWithOnlyMenuTitle extends Migration {

	public function up()
	{
		$pages = Page::whereTitle('')
			->orWhereNull('title')
			->with('menuItem')
			->get();

		foreach($pages as $item) {
			DB::table('pages')
				->whereId($item->id)
				->update([
					'title' => $item->menuItem->menu_title,
					'is_show_title' => 0
				]);
		}
	}

	public function down()
	{
		$pages = Page::whereIsShowTitle(0)
			->with('menuItem')
			->get();

		foreach($pages as $item) {
			DB::table('pages')
				->whereId($item->id)
				->update([
					'is_show_title' => 1
				]);
		}
	}

}

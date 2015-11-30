<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnMenuTitleFromMenusTable extends Migration {

	public function up()
	{
		Schema::table('menus', function (Blueprint $table) {
			$table->dropColumn('menu_title');
		});
	}

	public function down()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->string('menu_title', 200)->after('is_container');
		});

		$pages = Page::all();
		foreach($pages as $page) {
			if($page->menuItem) {
				$page->menu_title = $page->menuItem->menu_title;
				$page->save();
			}
		}
	}

}

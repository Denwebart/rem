<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAndFillColumnMenuTitleIntoPagesTable extends Migration {

	public function up()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->string('menu_title', 200)->after('title');
		});

		$items = Menu::all();
		foreach($items as $item) {
			if($item->page) {
				$item->page->menu_title = $item->menu_title;
				$item->page->save();
			}
		}
	}

	public function down()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->dropColumn('menu_title');
		});
	}

}

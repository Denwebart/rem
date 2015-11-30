<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAndFillColumnMenuTitleIntoPagesTable extends Migration {

	public function up()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->dropColumn('menu_title');
		});
	}

	public function down()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->string('menu_title', 200);
		});

		$items = Menu::all();
		foreach($items as $item) {
			if($items->page) {
				$item->menu_title = $items->page->menu_title;
				$item->save();
			}
		}
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration {

	public function up()
	{
		Schema::create('menus', function (Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('type')->default(1);
			$table->integer('page_id');
			$table->integer('parent_id')->default(0);
			$table->integer('position')->default(0);
			$table->integer('show_submenu')->default(0);
			$table->string('menu_title', 200);
		});
	}

	public function down()
	{
		Schema::drop('menus');
	}

}

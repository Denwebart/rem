<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnShowSubmenuFromMenusTable extends Migration {

	public function up()
	{
		Schema::table('menus', function (Blueprint $table) {
			$table->dropColumn('show_submenu');
		});
	}

	public function down()
	{
		Schema::table('menus', function (Blueprint $table) {
			$table->integer('show_submenu')->default(0);
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnMenuTitleShosSubmenuFromPagesTable extends Migration {

	public function up()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->dropColumn('menu_title');
			$table->dropColumn('show_submenu');
		});
	}

	public function down()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->string('menu_title', 200)->after('is_container');
			$table->boolean('show_submenu')->default(0)->after('is_container');
		});
	}

}

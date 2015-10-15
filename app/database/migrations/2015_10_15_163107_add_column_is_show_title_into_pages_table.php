<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsShowTitleIntoPagesTable extends Migration {

	public function up()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->boolean('is_show_title')->default(1)->after('is_container');
		});
	}

	public function down()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->dropColumn('is_show_title');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeIntoPagesTable extends Migration {

	public function up()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->tinyInteger('type')->default(1)->after('id');
		});
	}

	public function down()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->dropColumn('type');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAliasIntoHonorsTable extends Migration {

	public function up()
	{
		Schema::table('honors', function (Blueprint $table) {
			$table->string('alias', 100)->after('id');
		});
	}

	public function down()
	{
		Schema::table('honors', function (Blueprint $table) {
			$table->dropColumn('alias');
		});
	}

}

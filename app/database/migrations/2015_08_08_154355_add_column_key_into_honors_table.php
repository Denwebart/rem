<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKeyIntoHonorsTable extends Migration {

	public function up()
	{
		Schema::table('honors', function (Blueprint $table) {
			$table->string('key', 50)->after('id')->nullable();
		});
	}

	public function down()
	{
		Schema::table('honors', function (Blueprint $table) {
			$table->dropColumn('key');
		});
	}

}

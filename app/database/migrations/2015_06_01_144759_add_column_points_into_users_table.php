<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPointsIntoUsersTable extends Migration {

	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->integer('points')->after('role')->default(0);
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('points');
		});
	}

}

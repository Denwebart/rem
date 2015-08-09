<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLastActivityIntoUsersTable extends Migration {

	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->timestamp('last_activity')->nullable();
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('last_activity');
		});
	}

}

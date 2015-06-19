<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnIpFromUsersTable extends Migration {

	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('ip');
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->string('ip', 20)->nullable();
		});
	}

}

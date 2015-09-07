<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAliasIntoUsersTable extends Migration {

	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->string('alias', 150)->after('login');
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('alias');
		});
	}

}

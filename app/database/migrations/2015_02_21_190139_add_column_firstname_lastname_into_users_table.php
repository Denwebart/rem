<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFirstnameLastnameIntoUsersTable extends Migration {

	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->renameColumn('name', 'login');
			$table->string('firstname', 100)->nullable()->after('email');
			$table->string('lastname', 100)->nullable()->after('firstname');
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->renameColumn('login', 'name');
			$table->dropColumn('firstname');
			$table->dropColumn('lastname');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration {

	public function up()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('is_published');
			$table->boolean('is_active')->default(0);
			$table->string('activationCode');
			$table->string('remember_token', 100)->nullable()->index();
			$table->string('password', 100);
			$table->dropColumn('user_id');
		});
	}

	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('is_active');
			$table->boolean('is_published')->default(0);
			$table->dropColumn('activationCode');
			$table->dropColumn('remember_token');
			$table->dropColumn('password');
			$table->integer('user_id')->nullable();
		});
	}

}

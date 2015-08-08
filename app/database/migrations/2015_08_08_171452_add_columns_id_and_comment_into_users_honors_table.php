<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIdAndCommentIntoUsersHonorsTable extends Migration {

	public function up()
	{
		Schema::table('users_honors', function (Blueprint $table) {
			$table->dropPrimary(['user_id', 'honor_id']);
		});
		Schema::table('users_honors', function (Blueprint $table) {
			$table->increments('id');
			$table->string('comment', 50)->after('honor_id')->nullable();
		});
	}

	public function down()
	{
		Schema::table('users_honors', function (Blueprint $table) {
			$table->dropColumn('id');
			$table->dropColumn('comment');
			$table->primary(['user_id', 'honor_id']);
		});
	}

}

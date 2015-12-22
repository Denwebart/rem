<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsForAdminIntoSettingsTable extends Migration {

	public function up()
	{
		Schema::table('users_settings', function (Blueprint $table) {
			$table->boolean('notification_all_new_user')->default(0);
			$table->boolean('notification_all_new_question')->default(0);
			$table->boolean('notification_all_new_article')->default(0);
			$table->boolean('notification_all_new_answer')->default(0);
			$table->boolean('notification_all_new_comment')->default(0);
		});
	}

	public function down()
	{
		Schema::table('users_settings', function (Blueprint $table) {
			$table->dropColumn('notification_all_new_user');
			$table->dropColumn('notification_all_new_question');
			$table->dropColumn('notification_all_new_article');
			$table->dropColumn('notification_all_new_answer');
			$table->dropColumn('notification_all_new_comment');
		});
	}

}

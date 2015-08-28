<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersSettingsTable extends Migration {

	public function up()
	{
		Schema::create('users_settings', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->boolean('notification_deleted')->default(1);
			$table->boolean('notification_points')->default(1);
			$table->boolean('notification_new_comments')->default(1);
			$table->boolean('notification_new_answers')->default(1);
			$table->boolean('notification_like_dislike')->default(1);
			$table->boolean('notification_best_answer')->default(1);
			$table->boolean('notification_rating')->default(1);
			$table->boolean('notification_journal_subscribed')->default(1);
			$table->boolean('notification_question_subscribed')->default(1);
			$table->boolean('notification_banned')->default(1);
			$table->boolean('notification_role_changed')->default(1);
		});
	}

	public function down()
	{
		Schema::drop('users_settings');
	}

}

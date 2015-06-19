<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('ban_notifications', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->string('message', 500)->nullable();
			$table->timestamp('ban_at')->nullable();
			$table->timestamp('unban_at')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('ban_notifications');
	}

}

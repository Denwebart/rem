<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->tinyInteger('type');
			$table->string('message', 500);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}

}

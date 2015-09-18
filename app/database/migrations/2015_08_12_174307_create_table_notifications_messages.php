<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNotificationsMessages extends Migration {

	public function up()
	{
		Schema::create('notifications_messages', function (Blueprint $table) {
			$table->increments('id');
			$table->string('message', 1000);
			$table->string('desctiption', 500)->nullable();
			$table->primary('id');
		});
	}

	public function down()
	{
		Schema::drop('notifications_messages');
	}

}

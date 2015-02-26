<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	public function up()
	{
		Schema::create('messages', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id_sender')->unsigned();
			$table->foreign('user_id_sender')->references('id')->on('users')->onDelete('cascade');
			$table->integer('user_id_recipient')->unsigned();
			$table->foreign('user_id_recipient')->references('id')->on('users')->onDelete('cascade');
			$table->text('message');
			$table->timestamps();
			$table->timestamp('read_at');
		});

	}

	public function down()
	{
		Schema::drop('messages');
	}
}

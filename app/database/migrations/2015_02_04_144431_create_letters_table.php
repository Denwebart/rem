<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersTable extends Migration {

	public function up()
	{
		Schema::create('letters', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('ip', 20)->nullable();
			$table->string('name', 100)->nullable();
			$table->string('email', 100)->nullable();
			$table->string('subject', 500)->nullable();
			$table->text('message');
			$table->timestamps();
			$table->timestamp('read_at');
		});

	}

	public function down()
	{
		Schema::drop('letters');
	}
}

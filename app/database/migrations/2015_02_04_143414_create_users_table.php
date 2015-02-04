<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->boolean('is_published')->default(0);
			$table->string('name', 150);
			$table->string('email', 150)->unique();
			$table->string('ip', 20)->nullable();
			$table->string('description', 3000)->nullable();
			$table->string('car_brand', 150)->nullable();
			$table->string('profession', 150)->nullable();
			$table->string('city', 150)->nullable();
			$table->string('country', 150)->nullable();
			$table->timestamps();
		});

	}

	public function down()
	{
		Schema::drop('users');
	}

}

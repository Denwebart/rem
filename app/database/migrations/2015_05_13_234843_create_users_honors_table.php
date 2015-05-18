<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersHonorsTable extends Migration {

	public function up()
	{
		Schema::create('users_honors', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('honor_id');
			$table->timestamps();
			$table->primary(array('user_id', 'honor_id'));
		});
	}

	public function down()
	{
		Schema::drop('users_honors');
	}

}

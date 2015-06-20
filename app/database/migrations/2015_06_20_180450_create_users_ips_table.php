<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersIpsTable extends Migration {

	public function up()
	{
		Schema::create('users_ips', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('ip_id');
			$table->timestamps();
			$table->primary(array('user_id', 'ip_id'));
		});
	}

	public function down()
	{
		Schema::drop('users_ips');
	}

}

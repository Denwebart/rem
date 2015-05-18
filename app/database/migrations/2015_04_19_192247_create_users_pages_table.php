<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersPagesTable extends Migration {

	public function up()
	{
		Schema::create('users_pages', function (Blueprint $table) {
			$table->integer('user_id');
			$table->integer('page_id');
			$table->timestamps();
			$table->primary(array('user_id', 'page_id'));
		});
	}

	public function down()
	{
		Schema::drop('users_pages');
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHonorsTable extends Migration {

	public function up()
	{
		Schema::create('honors', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title', 100);
			$table->string('image', 100)->nullable();
			$table->text('description');
		});
	}

	public function down()
	{
		Schema::drop('honors');
	}

}

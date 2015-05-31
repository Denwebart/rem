<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisingTable extends Migration {

	public function up()
	{
		Schema::create('advertising', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('position');
			$table->string('title', 100)->nullable();
			$table->text('text');
			$table->boolean('is_active');
			$table->timestamps();
		});

	}

	public function down()
	{
		Schema::drop('advertising');
	}

}

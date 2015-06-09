<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesTable extends Migration {

	public function up()
	{
		Schema::create('rules', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('position')->default(0);
			$table->boolean('is_published')->default(0);
			$table->string('title', 500)->nullable();
			$table->string('description', 2000)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('rules');
	}

}

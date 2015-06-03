<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration {

	public function up()
	{
		Schema::create('tags', function (Blueprint $table) {
			$table->increments('id');
			$table->string('image', 300)->nullable();
			$table->string('title', 100);
		});
	}

	public function down()
	{
		Schema::drop('tags');
	}

}

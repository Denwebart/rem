<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersImagesTable extends Migration {

	public function up()
	{
		Schema::create('users_images', function (Blueprint $table) {
			$table->increments('id');
			$table->boolean('is_published')->default(0);
			$table->string('title', 300)->nullable();
			$table->string('image', 300);
			$table->string('image_alt', 1000)->nullable();
			$table->string('description', 3000)->nullable();
			$table->integer('votes_like')->default(0);
			$table->integer('votes_dislike')->default(0);
			$table->timestamps();
			$table->timestamp('published_at');
		});
	}

	public function down()
	{
		Schema::drop('users_images');
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	public function up()
	{
		Schema::create('comments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('parent_id')->default(0);
			$table->integer('user_id')->nullable();
			$table->integer('page_id');
			$table->boolean('is_published')->default(0);
			$table->integer('votes_like')->default(0);
			$table->integer('votes_dislike')->default(0);
			$table->text('comment');
			$table->timestamps();
			$table->timestamp('published_at');
		});
	}

	public function down()
	{
		Schema::drop('comments');
	}

}

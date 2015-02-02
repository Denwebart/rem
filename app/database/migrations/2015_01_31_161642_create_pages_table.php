<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

	public function up()
	{
		Schema::create('pages', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('parent_id')->default(0);
			$table->integer('user_id');
			$table->boolean('is_published')->default(0);
			$table->string('alias', 300);
			$table->boolean('is_container')->default(0);
			$table->boolean('show_submenu')->default(0);
			$table->string('menu_title', 200);
			$table->string('title', 500);
			$table->string('image', 300)->nullable();
			$table->string('image_alt', 1000)->nullable();
			$table->integer('views')->default(0);
			$table->integer('voters')->default(0);
			$table->integer('votes')->default(0);
			$table->string('introtext', 2000)->nullable();
			$table->text('content')->nullable();
			$table->timestamps();
			$table->timestamp('published_at');
			$table->string('meta_title', 600)->nullable();
			$table->string('meta_desc', 1500)->nullable();
			$table->string('meta_key', 1500)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('pages');
	}

}

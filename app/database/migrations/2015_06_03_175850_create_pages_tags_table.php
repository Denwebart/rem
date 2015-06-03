<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTagsTable extends Migration {

	public function up()
	{
		Schema::create('pages_tags', function (Blueprint $table) {
			$table->integer('page_id');
			$table->integer('tag_id');
			$table->primary(['page_id', 'tag_id']);
		});
	}

	public function down()
	{
		Schema::drop('pages_tags');
	}

}

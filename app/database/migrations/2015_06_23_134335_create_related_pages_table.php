<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedPagesTable extends Migration {

	public function up()
	{
		Schema::create('related_pages', function (Blueprint $table) {
			$table->integer('page_id');
			$table->integer('related_page_id');
			$table->tinyInteger('type')->default(1);
			$table->timestamps();
			$table->primary(['page_id', 'related_page_id']);
		});
	}

	public function down()
	{
		Schema::drop('related_pages');
	}

}

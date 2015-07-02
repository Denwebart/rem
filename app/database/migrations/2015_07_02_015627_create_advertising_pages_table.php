<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisingPagesTable extends Migration {

	public function up()
	{
		Schema::create('advertising_pages', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('advertising_id');
			$table->integer('page_type')->default(0);
		});
	}

	public function down()
	{
		Schema::drop('advertising_pages');
	}

}

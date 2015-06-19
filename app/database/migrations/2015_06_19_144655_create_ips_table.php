<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpsTable extends Migration {

	public function up()
	{
		Schema::create('ips', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('ip', 20)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('ips');
	}

}

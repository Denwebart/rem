<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('key', 100)->unique();
			$table->boolean('type', 100);
			$table->string('title', 100);
			$table->string('description', 500)->nullable();
			$table->string('value', 500)->nullable();
			$table->boolean('isActive');
			$table->timestamps();
		});

	}

	public function down()
	{
		Schema::drop('settings');
	}

}

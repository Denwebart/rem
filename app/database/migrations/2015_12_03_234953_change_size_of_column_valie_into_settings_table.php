<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSizeOfColumnValieIntoSettingsTable extends Migration {

	public function up()
	{
		$settings = Setting::all();
		Schema::drop('settings');
		Schema::create('settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('key', 100)->unique();
			$table->string('category', 100)->nullable();
			$table->boolean('type');
			$table->string('title', 100);
			$table->string('description', 500)->nullable();
			$table->text('value')->nullable();
			$table->boolean('is_active');
			$table->timestamps();
		});
		foreach ($settings as $setting) {
			Setting::create($setting->toArray());
		}

	}

	public function down()
	{
		$settings = Setting::all();
		Schema::drop('settings');
		Schema::create('settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('key', 100)->unique();
			$table->string('category', 100)->nullable();
			$table->boolean('type');
			$table->string('title', 100);
			$table->string('description', 500)->nullable();
			$table->string('value', 1500)->nullable();
			$table->boolean('is_active');
			$table->timestamps();
		});
		foreach ($settings as $setting) {
			Setting::create($setting->toArray());
		}
	}

}

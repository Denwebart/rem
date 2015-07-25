<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableSiteCopyright extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'copyright',
				'category' => 'Site',
				'type' => Setting::TYPE_HTML,
				'title' => 'Копирайт',
				'description' => '',
				'value' => 'При использовании авторских статей ссылка на сайт обязательна. © <a href="http://avtorem.info" title="Школа авторемонта">www.avtorem.info</a> 2010',
				'is_active' => 1,
			],
		]);
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', [
			'copyright',
		])->delete();
	}
}

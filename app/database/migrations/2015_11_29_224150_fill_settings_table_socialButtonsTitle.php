<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableSocialButtonsTitle extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'socialButtonsTitle',
				'category' => 'Site',
				'type' => Setting::TYPE_TEXT,
				'title' => 'Заголовок виджета с социальными кнопками',
				'description' => '',
				'value' => 'Понравилась статья? Поделись ею на своей странице!',
				'is_active' => 1,
			],
		]);
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', [
			'socialButtonsTitle',
		])->delete();
	}

}

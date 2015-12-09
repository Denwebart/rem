<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableTheme extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'theme',
				'category' => 'Site',
				'type' => Setting::TYPE_TEXT,
				'title' => 'Тема оформления сайта',
				'description' => '',
				'value' => '',
				'is_active' => 1,
			],
		]);
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', [
			'theme',
		])->delete();
	}

}

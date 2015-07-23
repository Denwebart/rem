<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableSiteTitleAndSlogan extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'siteTitle',
				'category' => 'Site',
				'type' => Setting::TYPE_TEXT,
				'title' => 'Заголовок сайта',
				'description' => '',
				'value' => 'Школа авторемонта',
				'is_active' => 1,
			],
			[
				'key' => 'siteSlogan',
				'category' => 'Site',
				'type' => Setting::TYPE_TEXT,
				'title' => 'Слоган сайта',
				'description' => '',
				'value' => 'Статьи, советы и рекомендации по ремонту и обслуживанию автомобилей своими руками',
				'is_active' => 1,
			],
		]);
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', [
			'siteTitle',
			'siteSlogan',
		])->delete();
	}

}

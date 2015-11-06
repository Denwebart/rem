<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableSiteEmail extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'siteEmail',
				'category' => 'Email',
				'type' => Setting::TYPE_TEXT,
				'title' => 'Email сайта',
				'description' => '',
				'value' => '',
				'is_active' => 1,
			],
		]);
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', [
			'siteEmail',
		])->delete();
	}

}

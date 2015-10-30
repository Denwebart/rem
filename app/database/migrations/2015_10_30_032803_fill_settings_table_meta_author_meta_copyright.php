<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableMetaAuthorMetaCopyright extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'metaCopyright',
				'category' => 'Site',
				'type' => Setting::TYPE_TEXT,
				'title' => 'Мета-тег Copyright',
				'description' => '',
				'value' => '',
				'is_active' => 1,
			],
			[
				'key' => 'metaAuthor',
				'category' => 'Site',
				'type' => Setting::TYPE_TEXT,
				'title' => 'Мета-тег Author',
				'description' => '',
				'value' => '',
				'is_active' => 1,
			],
		]);
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', [
			'metaCopyright',
			'metaAuthor',
		])->delete();
	}

}

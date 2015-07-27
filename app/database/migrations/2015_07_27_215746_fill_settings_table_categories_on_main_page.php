<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableCategoriesOnMainPage extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'categoriesOnMainPage',
				'category' => 'MainPage',
				'type' => Setting::TYPE_HTML,
				'title' => 'Статьи на главной',
				'description' => 'Перечень категорий, статьи из которых будут выведены на главной странице сайта.',
				'value' => '3, 4, 5, 6, 9',
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

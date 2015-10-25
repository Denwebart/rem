<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableModertion extends Migration {

	public function up()
	{
		DB::table('settings')->insert([
			[
				'key' => 'articlesPremoderation',
				'category' => 'Moderation',
				'type' => Setting::TYPE_BOOLEAN,
				'title' => 'Премодерация статей',
				'description' => '',
				'value' => 0,
				'is_active' => 1,
			],
			[
				'key' => 'questionsPremoderation',
				'category' => 'Moderation',
				'type' => Setting::TYPE_BOOLEAN,
				'title' => 'Премодерация вопросов',
				'description' => '',
				'value' => 0,
				'is_active' => 1,
			],
			[
				'key' => 'commentsPremoderationForRegistered',
				'category' => 'Moderation',
				'type' => Setting::TYPE_BOOLEAN,
				'title' => 'Премодерация комментариев для зарегистрированных пользователей',
				'description' => '',
				'value' => 0,
				'is_active' => 1,
			],
			[
				'key' => 'commentsPremoderationForUnregistered',
				'category' => 'Moderation',
				'type' => Setting::TYPE_BOOLEAN,
				'title' => 'Премодерация комментариев для незарегистрированных пользователей',
				'description' => '',
				'value' => 1,
				'is_active' => 1,
			],
		]);
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', [
			'articlesPremoderation',
			'questionsPremoderation',
			'commentsPremoderationForRegistered',
			'commentsPremoderationForUnregistered',
		])->delete();
	}

}

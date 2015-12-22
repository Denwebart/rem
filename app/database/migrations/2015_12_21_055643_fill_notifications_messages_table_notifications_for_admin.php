<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillNotificationsMessagesTableNotificationsForAdmin extends Migration {

	public function up()
	{
		DB::table('notifications_messages')->insert([
			[
				'id' => '27',
				'message' => 'Зарегистрировался новый пользователь <a href="[linkToUser]">"[user]"</a>',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li></ul>',
				'description' => 'Зарегистрировался новый пользователь',
			],
			[
				'id' => '28',
				'message' => 'Добавлен новый вопрос : <a href="[linkToPage]">"[pageTitle]"</a>',
				'variables' => '<ul><li>[pageTitle]</li><li>[linkToPage]</li><li>[user]</li><li>[linkToUser]</li></ul>',
				'description' => 'Кто-то добавил новый вопрос на сайт',
			],
			[
				'id' => '29',
				'message' => 'Добавлена новая статья : <a href="[linkToPage]">"[pageTitle]"</a>',
				'variables' => '<ul><li>[pageTitle]</li><li>[linkToPage]</li><li>[user]</li><li>[linkToUser]</li></ul>',
				'description' => 'Кто-то добавил новую статью на сайт',
			],
			[
				'id' => '30',
				'message' => '[user] добавил ответ на вопрос <a href="[linkToPage]">"[pageTitle]"</a>',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToComment]</li><li>[comment]</li></ul>',
				'description' => 'Кто-то добавил новый ответ на сайт',
			],
			[
				'id' => '31',
				'message' => '[user] оставил комментарий к статье <a href="[linkToPage]">"[pageTitle]"</a>',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToComment]</li><li>[comment]</li></ul>',
				'description' => 'Кто-то добавил новый комментарий на сайт',
			],
		]);
	}

	public function down()
	{
		DB::table('notifications_messages')->whereIn('id', [27,28,29,30,31])->delete();
	}

}

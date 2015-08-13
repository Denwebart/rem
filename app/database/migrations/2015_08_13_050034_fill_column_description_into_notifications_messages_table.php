<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillColumnDescriptionIntoNotificationsMessagesTable extends Migration {

	public function up()
	{
		$data = [
			1 => [
				'description' => 'Добавление баллов за комментарий',
				'variables' => '<ul><li>[linkToComment]</li><li>[comment]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			2 => [
				'description' => 'Добавление баллов за ответ',
				'variables' => '<ul><li>[linkToAnswer]</li><li>[answer]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			3 => [
				'description' => 'Добавление баллов за создание статьи',
				'variables' => '<ul><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			4 => [
				'description' => 'Добавление баллов за лучший ответ',
				'variables' => '<ul><li>[linkToAnswer]</li><li>[answer]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			5 => [
				'description' => 'Вычитание баллов за комментарий (если набрал меньше 0 голосов)',
				'variables' => '<ul><li>[linkToComment]</li><li>[comment]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			6 => [
				'description' => 'Вычитание баллов за ответ (если набрал меньше 0 голосов)',
				'variables' => '<ul><li>[linkToAnswer]</li><li>[answer]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			7 => [
				'description' => 'Вычитание баллов, если статья была удалена',
				'variables' => '<ul><li>[pageTitle]</li></ul>',
			],
			8 => [
				'description' => 'Вычитание баллов, если лучший комментарий был удален',
				'variables' => '<ul><li>[answer]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			9 => [
				'description' => 'Пользователь забанен',
				'variables' => '<ul><li>[bandescription]</li></ul>',
			],
			10 => [
				'description' => 'Пользователь разбанен',
				'variables' => '',
			],
			11 => [
				'description' => 'Кто-то оставил комментарий к статье/вопросу',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToComment]</li><li>[comment]</li></ul>',
			],
			12 => [
				'description' => 'Кто-то ответил на вопрос',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToAnswer]</li><li>[answer]</li></ul>',
			],
			13 => [
				'description' => 'Кому-то понравился комментарий',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToComment]</li><li>[comment]</li></ul>',
			],
			14 => [
				'description' => 'Кому-то не понравился комментарий',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToComment]</li><li>[comment]</li></ul>',
			],
			15 => [
				'description' => 'Кому-то понравился ответ',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToAnswer]</li><li>[answer]</li></ul>',
			],
			16 => [
				'description' => 'Кому-то не понравился комментарий',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToAnswer]</li><li>[answer]</li></ul>',
			],
			17 => [
				'description' => 'Ответ стал лучшим',
				'variables' => '<ul><li>[linkToPage]</li><li>[pageTitle]</li><li>[linkToAnswer]</li><li>[answer]</li></ul>',
			],
			18 => [
				'description' => 'Кто-то поставил оценку статье/вопросу',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[rating]</li><li>[linkToPage]</li><li>[pageTitle]</li></ul>',
			],
			19 => [
				'description' => 'Кто-то подписался на вопрос',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li></ul>',
			],
			20 => [
				'description' => 'Кто-то подписался на журнал',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li></ul>',
			],
			21 => [
				'description' => 'Кто-то отменил подписку на вопрос',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li><li>[linkToPage]</li><li>[pageTitle]</li></ul>',
			],
			22 => [
				'description' => 'Кто-то отменил подписку на журнал',
				'variables' => '<ul><li>[user]</li><li>[linkToUser]</li></ul>',
			],
			23 => [
				'description' => 'Права пользователя изменены',
				'variables' => '<ul><li>[role]</li></ul>',
			],
			24 => [
				'description' => 'Вычитание баллов, если комментарий был удален',
				'variables' => '<ul><li>[comment]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			25 => [
				'description' => 'Вычитание баллов, если ответ был удален',
				'variables' => '<ul><li>[answer]</li><li>[pageTitle]</li><li>[linkToPage]</li></ul>',
			],
			26 => [
				'description' => 'Вопрос был удален',
				'variables' => '<ul><li>[pageTitle]</li></ul>',
			],
		];
		foreach($data as $key => $value) {
			DB::table('notifications_messages')->where('id', '=', $key)->update($value);
		}
	}

	public function down()
	{
		DB::table('notifications_messages')->whereIn('id', range(1, 23))->update(['description' => '']);
	}

}
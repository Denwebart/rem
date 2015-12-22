<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillNotificationsMessagesTable extends Migration {

	public function up()
	{
		DB::table('notifications_messages')->insert([
			[
				'id' => '1',
				'message' => '+1 балл за <a href="[linkToComment]">комментарий</a>',
				'desctiption' => '[linkToComment][comment][pageTitle][linkToPage]',
			],
			[
				'id' => '2',
				'message' => '+1 балл за <a href="[linkToAnswer]">ответ</a>',
				'desctiption' => '[linkToAnswer][answer][pageTitle][linkToPage]',
			],
			[
				'id' => '3',
				'message' => '+5 баллов за создание <a href="[linkToPage]">статьи</a>',
				'desctiption' => '[pageTitle][linkToPage]',
			],[
				'id' => '4',
				'message' => '+4 балла за лучший <a href="[linkToAnswer]">ответ</a>',
				'desctiption' => '[linkToAnswer][answer][pageTitle][linkToPage]',
			],
			[
				'id' => '5',
				'message' => '-1 балл за плохой <a href="[linkToComment]">комментарий</a>',
				'desctiption' => '[linkToComment][comment][pageTitle][linkToPage]',
			],
			[
				'id' => '6',
				'message' => '-1 балл за плохой <a href="[linkToAnswer]">ответ</a>',
				'desctiption' => '[linkToAnswer][answer][pageTitle][linkToPage]',
			],
			[
				'id' => '7',
				'message' => '-5 баллов. Ваша статья "[pageTitle]" была удалена.',
				'desctiption' => '[pageTitle]',
			],
			[
				'id' => '8',
				'message' => '-5 баллов. Ваш лучший ответ был удален.',
				'desctiption' => '[answer][pageTitle][linkToPage]',
			],
			[
				'id' => '9',
				'message' => 'Вы были забанены. Причина: "[banMessage]"',
				'desctiption' => '[banMessage]',
			],
			[
				'id' => '10',
				'message' => 'Вы были разбанены.',
				'desctiption' => '',
			],
			[
				'id' => '11',
				'message' => '[user] оставил комментарий к вашей статье <a href="[linkToPage]">"[pageTitle]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle][linkToComment][comment]',
			],
			[
				'id' => '12',
				'message' => '[user] ответил на ваш вопрос <a href="[linkToPage]">"[pageTitle]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle][linkToAnswer][answer]',
			],
			[
				'id' => '13',
				'message' => '[user] одобрил ваш комментарий <a href="[linkToComment]">"[comment]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle][linkToComment][comment]',
			],
			[
				'id' => '14',
				'message' => '[user] осудил ваш комментарий <a href="[linkToComment]">"[comment]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle][linkToComment][comment]',
			],
			[
				'id' => '15',
				'message' => '[user] одобрил ваш ответ <a href="[linkToAnswer]">"[answer]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle][linkToAnswer][answer]',
			],
			[
				'id' => '16',
				'message' => '[user] осудил ваш ответ <a href="[linkToAnswer]">"[answer]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle][linkToAnswer][answer]',
			],
			[
				'id' => '17',
				'message' => 'Ваш <a href="[linkToAnswer]">ответ</a> стал лучшим!',
				'desctiption' => '[linkToPage][pageTitle][linkToAnswer][answer]',
			],
			[
				'id' => '18',
				'message' => '<a href="[linkToUser]">[user]</a> поставил оценку [rating] вашей статье <a href="[linkToPage]">"[pageTitle]"</a>',
				'desctiption' => '[user][linkToUser][rating][linkToPage][pageTitle]',
			],
			[
				'id' => '19',
				'message' => '[user] подписался на ваш вопрос <a href="[linkToPage]">"[pageTitle]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle]',
			],
			[
				'id' => '20',
				'message' => '[user] подписался на ваш журнал',
				'desctiption' => '[user][linkToUser]',
			],
			[
				'id' => '21',
				'message' => '[user] отменил подписку на ваш вопрос <a href="[linkToPage]">"[pageTitle]"</a>',
				'desctiption' => '[user][linkToUser][linkToPage][pageTitle]',
			],
			[
				'id' => '22',
				'message' => '[user] отменил подписку на ваш журнал',
				'desctiption' => '[user][linkToUser]',
			],
			[
				'id' => '23',
				'message' => 'Ваши права были изменены. Теперь вы [role]',
				'desctiption' => '[role]',
			],
			[
				'id' => '24',
				'message' => '-1 балл. Ваш комментарий "[comment]" был удален',
				'desctiption' => '[comment][pageTitle][linkToPage]',
			],
			[
				'id' => '25',
				'message' => '-1 балл. Ваш ответ "[answer]" был удален',
				'desctiption' => '[answer][pageTitle][linkToPage]',
			],
			[
				'id' => '26',
				'message' => 'Ваш вопрос "[pageTitle]" был удален',
				'desctiption' => '[pageTitle]',
			],
		]);
	}

	public function down()
	{
		DB::table('notifications_messages')->whereIn('id', range(1, 26))->delete();
	}

}

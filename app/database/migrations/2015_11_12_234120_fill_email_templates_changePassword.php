<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillEmailTemplatesChangePassword extends Migration {

	public function up()
	{
		DB::table('email_templates')->insert([
			[
				'key' => 'changePassword',
				'subject' => 'Смена пароля на сайте avtorem.info',
				'html' => '<h2>Восстановление пароля</h2><div>Для того, чтобы поменять пароль, перейдите по ссылке: [resetUrl].<br/>Это ссылка истекает через [expireTime] минут.</div>',
				'description' => 'Письмо пользователю: смена пароля.',
				'variables' => '<ul><li>[siteUrl]</li><li>[resetUrl]</li><li>[expireTime]</li></ul>',
			],
		]);
	}

	public function down()
	{
		DB::table('email_templates')->whereIn('key', [
			'changePassword',
		])->delete();
	}

}

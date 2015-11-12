<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillEmailTemplatesTable extends Migration {

	public function up()
	{
		DB::table('email_templates')->insert([
			[
				'key' => 'contactToUser',
				'subject' => 'Копия сообщения с сайта avtorem.info',
				'html' => '<h2>Здравствуйте, [user_name]</h2><p class="lead">Это копия сообщения, отправленного вами через контактную форму сайта<a href="[siteUrl]">[siteUrl]</a>.</p><table bgcolor="#ffffff"><tr><td><h3>[subject]</h3><p>[message_text]</p><p>Отправлено: [created_at] }}</p></td></tr></table>',
				'description' => 'Письмо пользователю: копия сообщения, отправленного пользователем с контактной формы.',
				'variables' => '<ul><li>[siteUrl]</li><li>[subject]</li><li>[message_text]</li><li>[created_at]</li><li>[user_name]</li><li>[email]</li></ul>',
			],
			[
				'key' => 'contactToAdmin',
				'subject' => 'Cообщение с сайта avtorem.info',
				'html' => '<h2>Сообщение с сайта <a href="[siteUrl]">[siteUrl]</a></h2><table bgcolor="#ffffff"><tr><td><h3>[subject]</h3><p>[message_text]</p><p>Отправлено: [created_at]</p><p>Отправитель: [user_name] (user_email)</p></td></tr></table>',
				'description' => 'Письмо админу: сообщение, отправленное пользователем с контактной формы.',
				'variables' => '<ul><li>[siteUrl]</li><li>[subject]</li><li>[message_text]</li><li>[created_at]</li><li>[user_name]</li><li>[email]</li></ul>',
			],
			[
				'key' => 'notification',
				'subject' => 'Уведомление с сайта avtorem.info',
				'html' => '<h2>Уведомление с сайта <a href="[siteUrl]">[siteUrl]</a></h2><table bgcolor="#ffffff"><tr><td><p>[notificationMessage]</p></td></tr></table>',
				'description' => 'Письмо пользователю: уведомление с сайта.',
				'variables' => '<ul><li>[notificationMessage]</li><li>[siteUrl]</li></ul>',
			],
			[
				'key' => 'activation',
				'subject' => 'Спасибо за регистрацию!',
				'html' => '<h2>Спасибо за регистрацию.</h2><p>Для подтверждения регистрации перейдите по ссылке [activationUrl].</p>',
				'description' => 'Письмо пользователю: активация аккаунта.',
				'variables' => '<ul><li>[siteUrl]</li><li>[activationUrl]</li></ul>',
			],
		]);
	}

	public function down()
	{
		DB::table('email_templates')->whereIn('key', [
			'activation',
			'notification',
			'contactToUser',
			'contactToAdmin',
		])->delete();
	}

}

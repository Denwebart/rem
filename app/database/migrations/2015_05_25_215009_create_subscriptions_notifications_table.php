<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('subscriptions_notifications', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('subscription_id');
			$table->string('message', 500);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('subscriptions_notifications');
	}

}

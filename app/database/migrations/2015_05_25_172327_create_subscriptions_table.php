<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration {

	public function up()
	{
		Schema::create('subscriptions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('page_id');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('subscriptions');
	}

}

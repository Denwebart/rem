<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnJournalIdIntoSubscriptionsTable extends Migration {

	public function up()
	{
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->dropColumn('page_id');
		});
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->integer('journal_id')->after('user_id')->nullable();
			$table->integer('page_id')->after('user_id')->nullable();
		});
	}

	public function down()
	{
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->dropColumn('journal_id');
			$table->dropColumn('page_id');
		});
		Schema::table('subscriptions', function (Blueprint $table) {
			$table->integer('page_id')->after('user_id');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDeletedIntoMessagesTable extends Migration {

	public function up()
	{
		Schema::table('messages', function (Blueprint $table) {
			$table->timestamp('deleted_sender')->default(null)->nullable();
			$table->timestamp('deleted_recipient')->default(null)->nullable();
		});
	}

	public function down()
	{
		Schema::table('messages', function (Blueprint $table) {
			$table->dropColumn('deleted_sender');
			$table->dropColumn('deleted_recipient');
		});
	}

}

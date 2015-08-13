<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDescriptionAndRenameDesctiptionToVariablesIntoNotificationsMessagesTable extends Migration {

	public function up()
	{
		Schema::table('notifications_messages', function (Blueprint $table) {
			$table->renameColumn('desctiption', 'variables');
			$table->string('description')->after('message');
		});
	}

	public function down()
	{
		Schema::table('notifications_messages', function (Blueprint $table) {
			$table->renameColumn('variables', 'desctiption');
			$table->dropColumn('description');
		});
	}

}

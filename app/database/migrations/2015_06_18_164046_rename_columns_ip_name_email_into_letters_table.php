<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnsIpNameEmailIntoLettersTable extends Migration {

	public function up()
	{
		Schema::table('letters', function($table)
		{
			$table->renameColumn('ip', 'user_ip');
			$table->renameColumn('name', 'user_name');
			$table->renameColumn('email', 'user_email');
		});
	}

	public function down()
	{
		Schema::table('letters', function($table)
		{
			$table->renameColumn('user_ip', 'ip');
			$table->renameColumn('user_name', 'name');
			$table->renameColumn('user_email', 'email');
		});
	}

}

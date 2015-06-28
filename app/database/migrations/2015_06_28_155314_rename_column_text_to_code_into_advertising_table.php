<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnTextToCodeIntoAdvertisingTable extends Migration {

	public function up()
	{
		Schema::table('advertising', function($table)
		{
			$table->renameColumn('text', 'code');
		});
	}

	public function down()
	{
		Schema::table('advertising', function($table)
		{
			$table->renameColumn('code', 'text');
		});
	}

}

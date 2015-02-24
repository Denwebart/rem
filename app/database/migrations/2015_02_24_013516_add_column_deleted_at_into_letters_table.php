<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDeletedAtIntoLettersTable extends Migration {

	public function up()
	{
		Schema::table('letters', function (Blueprint $table) {
			$table->timestamp('deleted_at')->nullable()->after('read_at');
		});
	}

	public function down()
	{
		Schema::table('letters', function (Blueprint $table) {
			$table->dropColumn('deleted_at');
		});
	}

}

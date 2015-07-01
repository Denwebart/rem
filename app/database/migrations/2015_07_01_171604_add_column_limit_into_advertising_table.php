<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLimitIntoAdvertisingTable extends Migration {

	public function up()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->tinyInteger('limit')->after('code')->default(5);
		});
	}

	public function down()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->dropColumn('limit');
		});
	}

}

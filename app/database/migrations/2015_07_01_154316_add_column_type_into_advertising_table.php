<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTypeIntoAdvertisingTable extends Migration {

	public function up()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->boolean('type')->after('id')->default(1);
		});
	}

	public function down()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->dropColumn('type');
		});
	}

}

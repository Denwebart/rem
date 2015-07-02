<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnPageTypeFromAdvertisingTable extends Migration {

	public function up()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->dropColumn('page_type');
		});
	}

	public function down()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->tinyInteger('page_type')->after('id')->default(0);
		});
	}

}

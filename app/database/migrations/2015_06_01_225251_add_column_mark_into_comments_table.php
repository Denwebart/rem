<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMarkIntoCommentsTable extends Migration {

	public function up()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->boolean('mark')->after('comment')->default(0);
		});
	}

	public function down()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->dropColumn('mark');
		});
	}

}

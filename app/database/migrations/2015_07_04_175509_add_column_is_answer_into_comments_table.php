<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsAnswerIntoCommentsTable extends Migration {

	public function up()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->boolean('is_answer')->after('id')->default(0);
		});
	}

	public function down()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->dropColumn('is_answer');
		});
	}

}

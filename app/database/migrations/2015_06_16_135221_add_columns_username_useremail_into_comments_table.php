<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsUsernameUseremailIntoCommentsTable extends Migration {

	public function up()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->string('user_name', 150)->after('user_id')->nullable();
			$table->string('user_email', 150)->after('user_id')->nullable();
		});
	}

	public function down()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->dropColumn('user_name');
			$table->dropColumn('user_email');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsUseripIntoCommentsTable extends Migration {

	public function up()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->string('user_ip', 20)->after('user_name')->nullable();
		});
	}

	public function down()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->dropColumn('user_ip');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceIpColumnOnIpIdIntoCommentsAndLettersTable extends Migration {

	public function up()
	{
		foreach (['letters', 'comments'] as $table) {
			Schema::table($table, function (Blueprint $table) {
				$table->dropColumn('user_ip');
				$table->integer('ip_id')->after('user_id')->nullable();
			});
		}
	}

	public function down()
	{
		foreach (['letters', 'comments'] as $table) {
			Schema::table($table, function (Blueprint $table) {
				$table->dropColumn('ip_id');
				$table->string('user_ip', 20)->nullable();
			});
		}
	}

}

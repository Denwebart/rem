<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIpsTable extends Migration {

	public function up()
	{
		Schema::table('ips', function (Blueprint $table) {
			$table->dropColumn('user_id');
			$table->boolean('is_banned')->default(0);
			$table->timestamp('ban_at')->nullable();
			$table->timestamp('unban_at')->nullable();
		});
	}

	public function down()
	{
		Schema::table('ips', function (Blueprint $table) {
			$table->integer('user_id')->nullable();
			$table->dropColumn('is_banned');
			$table->dropColumn('ban_at');
			$table->dropColumn('unban_at');
		});
	}

}

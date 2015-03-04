<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReadAtIntoCommentsTable extends Migration {

	public function up()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->timestamp('read_at')->nullable()->after('published_at');
		});
	}

	public function down()
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->dropColumn('read_at');
		});
	}

}

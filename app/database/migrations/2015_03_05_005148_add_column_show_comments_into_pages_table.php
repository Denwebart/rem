<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnShowCommentsIntoPagesTable extends Migration {

	public function up()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->boolean('show_comments')->after('show_submenu')->default(0);
		});
	}

	public function down()
	{
		Schema::table('pages', function (Blueprint $table) {
			$table->dropColumn('show_comments');
		});
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsIntoAdvertisingTable extends Migration {

	public function up()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->tinyInteger('area')->after('id');
			$table->boolean('access')->after('title')->default(0);
			$table->boolean('is_show_title')->after('title')->default(0);
			$table->string('description', 1000)->after('code')->nullable();
		});
	}

	public function down()
	{
		Schema::table('advertising', function (Blueprint $table) {
			$table->dropColumn('area');
			$table->dropColumn('access');
			$table->dropColumn('is_show_title');
			$table->dropColumn('description');
		});
	}

}

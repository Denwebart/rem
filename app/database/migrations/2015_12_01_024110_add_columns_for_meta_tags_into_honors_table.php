<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsForMetaTagsIntoHonorsTable extends Migration {

	public function up()
	{
		Schema::table('honors', function (Blueprint $table) {
			$table->string('meta_title', 600)->nullable();
			$table->string('meta_desc', 1500)->nullable();
			$table->string('meta_key', 1500)->nullable();
		});
	}

	public function down()
	{
		Schema::table('honors', function (Blueprint $table) {
			$table->dropColumn('meta_title');
			$table->dropColumn('meta_desc');
			$table->dropColumn('meta_key');
		});
	}

}

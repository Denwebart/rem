<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSizeOfColumnDescriptionIntoUsersImagesTable extends Migration {

	public function up()
	{
		Schema::table('users_images', function (Blueprint $table) {
			$table->dropColumn('description');
		});
		Schema::table('users_images', function (Blueprint $table) {
			$table->text('description')->after('title');
		});
	}

	public function down()
	{
		Schema::table('users_images', function (Blueprint $table) {
			$table->dropColumn('description');
		});
		Schema::table('users_images', function (Blueprint $table) {
			$table->string('description', 3000)->nullable()->after('title');
		});
	}

}

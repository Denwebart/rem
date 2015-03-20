<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserIdIntoUsersImagesTable extends Migration {

	public function up()
	{
		Schema::table('users_images', function (Blueprint $table) {
			$table->integer('user_id')->after('id');
		});
	}

	public function down()
	{
		Schema::table('users_images', function (Blueprint $table) {
			$table->dropColumn('user_id');
		});
	}

}

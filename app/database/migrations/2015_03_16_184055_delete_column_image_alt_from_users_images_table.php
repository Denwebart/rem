<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnImageAltFromUsersImagesTable extends Migration {

	public function up()
	{
		Schema::table('users_images', function(Blueprint $table)
		{
			$table->dropColumn('image_alt');
		});
	}

	public function down()
	{
		Schema::table('users_images', function(Blueprint $table)
		{
			$table->string('image_alt', 1000)->nullable();
		});
	}

}

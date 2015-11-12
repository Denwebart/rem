<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplatesTable extends Migration {

	public function up()
	{
		Schema::create('email_templates', function (Blueprint $table) {
			$table->increments('id');
			$table->string('key', 100)->unique();
			$table->string('subject', 500);
			$table->text('html');
			$table->string('description', 500)->nullable();
			$table->string('variables', 500)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('email_templates');
	}

}

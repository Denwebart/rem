<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillingFillPublishedAt extends Migration {

	protected $tablesWithPublishedField = ['pages', 'users_images'];

	public function up()
	{
		foreach ($this->tablesWithPublishedField as $tableName) {
			DB::update('UPDATE ' . $tableName . ' SET published_at = "' . date('Y:m:d H:i:s') . '" WHERE published_at IS NULL AND is_published = 1');
		}
	}

	public function down()
	{
		foreach ($this->tablesWithPublishedField as $tableName) {
			DB::update('UPDATE ' . $tableName . ' SET published_at = NULL');
		}
	}

}

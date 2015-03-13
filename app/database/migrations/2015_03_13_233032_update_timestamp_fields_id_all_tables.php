<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTimestampFieldsIdAllTables extends Migration {

    protected $tablesWithUpadtedField = ['messages', 'comments', 'letters', 'pages', 'settings', 'users', 'users_images'];
	protected $tablesWithReadField = ['messages', 'comments', 'letters'];
	protected $tablesWithPublishedField = ['pages', 'users_images'];
	protected $tablesWithDeletedField = ['letters'];

	public function up()
	{
		// updated_at
		foreach ($this->tablesWithUpadtedField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('updated_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('updated_at')->nullable()->after('created_at');
			});
		}

		// read_at
		foreach ($this->tablesWithReadField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('read_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('read_at')->nullable()->after('updated_at');
			});
		}

		// deleted_at
		foreach ($this->tablesWithDeletedField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('deleted_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('deleted_at')->nullable()->after('updated_at');
			});
		}

		// published_at
		foreach ($this->tablesWithPublishedField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('published_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('published_at')->nullable()->after('updated_at');
			});
		}
	}

	public function down()
	{
		// updated_at
		foreach ($this->tablesWithUpadtedField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('updated_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('updated_at')->after('created_at');
			});
		}

		// read_at
		foreach ($this->tablesWithReadField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('read_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('read_at')->after('updated_at');
			});
		}

		// deleted_at
		foreach ($this->tablesWithDeletedField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('deleted_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('deleted_at')->after('updated_at');
			});
		}

		// published_at
		foreach ($this->tablesWithPublishedField as $tableName) {
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->dropColumn('published_at');
			});
			Schema::table($tableName, function(Blueprint $table)
			{
				$table->timestamp('published_at')->after('updated_at');
			});
		}
	}

}

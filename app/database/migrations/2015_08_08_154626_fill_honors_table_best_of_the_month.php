<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillHonorsTableBestOfTheMonth extends Migration {

	public function up()
	{
		DB::table('honors')->insert([
			[
				'key' => 'bestWriterOfMonth',
				'alias' => 'luchshij-pisatel-mesyatsa',
				'title' => 'Лучший писатель месяца',
				'image' => '',
				'description' => '',
			],
			[
				'key' => 'bestRespondentOfMonth',
				'alias' => 'luchshij-sovetchik-mesyatsa',
				'title' => 'Лучший советчик месяца',
				'image' => '',
				'description' => '',
			],
			[
				'key' => 'bestCommentatorOfMonth',
				'alias' => 'luchshij-kommentator-mesyatsa',
				'title' => 'Лучший комментатор месяца',
				'image' => '',
				'description' => '',
			],
		]);
	}

	public function down()
	{
		DB::table('honors')->whereIn('key', [
			'bestWriterOfMonth',
			'bestRespondentOfMonth',
			'bestCommentatorOfMonth',
		])->delete();
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillHonorsTableBestOfTheYear extends Migration {

	public function up()
	{
		DB::table('honors')->insert([
			[
				'key' => 'bestWriterOfYear',
				'alias' => 'luchshij-pisatel-goda',
				'title' => 'Лучший писатель года',
				'image' => '',
				'description' => '',
			],
			[
				'key' => 'bestRespondentOfYear',
				'alias' => 'luchshij-sovetchik-goda',
				'title' => 'Лучший советчик года',
				'image' => '',
				'description' => '',
			],
			[
				'key' => 'bestCommentatorOfYear',
				'alias' => 'luchshij-kommentator-goda',
				'title' => 'Лучший комментатор года',
				'image' => '',
				'description' => '',
			],
		]);
	}

	public function down()
	{
		DB::table('honors')->whereIn('key', [
			'bestWriterOfYear',
			'bestRespondentOfYear',
			'bestCommentatorOfYear',
		])->delete();
	}

}

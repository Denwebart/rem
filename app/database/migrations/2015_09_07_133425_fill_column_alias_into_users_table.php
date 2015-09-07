<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillColumnAliasIntoUsersTable extends Migration {

	public function up()
	{
		$users = User::all();

		foreach($users as $user) {
			DB::table('users')->where('id', '=', $user->id)->update([
				'alias' => TranslitHelper::make($user->login)
			]);
		}
	}

	public function down()
	{
		DB::table('users')->update(['alias' => '']);
	}

}

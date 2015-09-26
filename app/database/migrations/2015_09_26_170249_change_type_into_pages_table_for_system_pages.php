<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeIntoPagesTableForSystemPages extends Migration {

	public function up()
	{
        DB::table('pages')
            ->whereIn('alias', ['/', 'karta-sajta', 'nagrady', 'tag', 'kontakty', 'rules'])
            ->update([
                'type' => Page::TYPE_SYSTEM_PAGE,
            ]);
	}

	public function down()
	{
        DB::table('pages')
            ->whereIn('alias', ['/', 'karta-sajta', 'nagrady', 'tag', 'kontakty', 'rules'])
            ->update([
                'type' => Page::TYPE_PAGE,
            ]);
	}

}

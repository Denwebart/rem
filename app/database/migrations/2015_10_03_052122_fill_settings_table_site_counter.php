<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableSiteCounter extends Migration {

    public function up()
    {
        DB::table('settings')->insert([
            [
                'key' => 'counter',
                'category' => 'Site',
                'type' => Setting::TYPE_HTML,
                'title' => 'Счетчик',
                'description' => '',
                'value' => '',
                'is_active' => 0,
            ],
        ]);
    }

    public function down()
    {
        DB::table('settings')->whereIn('key', [
            'counter',
        ])->delete();
    }

}

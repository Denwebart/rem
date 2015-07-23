<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillSettingsTableNumberOfItemsInSidebar extends Migration {

	public function up()
	{
		Schema::table('settings', function(Blueprint $table)
		{
			$table->string('category', 100)->after('key')->nullable();
			$table->renameColumn('isActive', 'is_active');
		});

		DB::table('settings')->insert(
			array(
				array(
					'key' => 'countOfLatest',
					'category' => 'SidebarWidget',
					'type' => 2,
					'title' => 'Количество пунктов в виджете "Самое новое"',
					'description' => '',
					'value' => 5,
					'is_active' => 1,
				),
				array(
					'key' => 'countOfBest',
					'category' => 'SidebarWidget',
					'type' => 2,
					'title' => 'Количество пунктов в виджете "ТОП-10"',
					'description' => '',
					'value' => 5,
					'is_active' => 1,
				),
				array(
					'key' => 'countOfPopular',
					'category' => 'SidebarWidget',
					'type' => 2,
					'title' => 'Количество пунктов в виджете "Самое популярное"',
					'description' => '',
					'value' => 5,
					'is_active' => 1,
				),
				array(
					'key' => 'countOfUnpopular',
					'category' => 'SidebarWidget',
					'type' => 2,
					'title' => 'Количество пунктов в виджете "Аутсайдеры"',
					'description' => '',
					'value' => 5,
					'is_active' => 1,
				),
				array(
					'key' => 'countOfComments',
					'category' => 'SidebarWidget',
					'type' => 2,
					'title' => 'Количество пунктов в виджете "Комментарии"',
					'description' => '',
					'value' => 5,
					'is_active' => 1,
				),
				array(
					'key' => 'countOfQuestions',
					'category' => 'SidebarWidget',
					'type' => 2,
					'title' => 'Количество пунктов в виджете "Новые вопросы"',
					'description' => '',
					'value' => 5,
					'is_active' => 1,
				),

			));
	}

	public function down()
	{
		DB::table('settings')->whereIn('key', ['countOfLatest', 'countOfBest', 'countOfPopular', 'countOfUnpopular', 'countOfComments', 'countOfQuestions'])->delete();

		Schema::table('settings', function(Blueprint $table)
		{
			$table->dropColumn('category');
			$table->renameColumn('is_active', 'isActive');
		});
	}

}

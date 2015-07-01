<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClearSettingsTableWhereCategorySidebarwidget extends Migration {

	public function up()
	{
		DB::table('settings')->where('category', 'SidebarWidget')->delete();
	}

	public function down()
	{
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

}

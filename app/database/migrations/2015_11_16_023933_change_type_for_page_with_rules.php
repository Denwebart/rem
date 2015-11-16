<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeForPageWithRules extends Migration {

	public function up()
	{
		$page = Page::whereAlias('pravila-sajta')->first();
		$page->type = Page::TYPE_SYSTEM_PAGE;
		$page->save();
	}

	public function down()
	{
		$page = Page::whereAlias('pravila-sajta')->first();
		$page->type = Page::TYPE_PAGE;
		$page->save();
	}

}

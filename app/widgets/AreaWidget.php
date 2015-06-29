<?php

class AreaWidget
{

	public function leftSidebar()
	{
		$advertising = Advertising::whereArea(Advertising::AREA_LEFT_SIDEBAR)
			->whereIsActive(1)
			->get();

		return (string) View::make('widgets.area.leftSidebar', compact('advertising'))->render();
	}

	public function rightSidebar()
	{
		$advertising = Advertising::whereArea(Advertising::AREA_RIGHT_SIDEBAR)
			->whereIsActive(1)
			->get();

		return (string) View::make('widgets.area.rightSidebar', compact('advertising'))->render();
	}

	public function contentTop()
	{
		$advertising = Advertising::whereArea(Advertising::AREA_CONTENT_TOP)
			->whereIsActive(1)
			->get();

		return (string) View::make('widgets.area.contentTop', compact('advertising'))->render();
	}

	public function contentMiddle()
	{
		$advertising = Advertising::whereArea(Advertising::AREA_CONTENT_MIDDLE)
			->whereIsActive(1)
			->get();

		return (string) View::make('widgets.area.contentMiddle', compact('advertising'))->render();
	}

	public function contentBottom()
	{
		$advertising = Advertising::whereArea(Advertising::AREA_CONTENT_BOTTOM)
			->whereIsActive(1)
			->get();

		return (string) View::make('widgets.area.contentBottom', compact('advertising'))->render();
	}

	public function siteBottom()
	{
		$advertising = Advertising::whereArea(Advertising::AREA_SITE_BOTTOM)
			->whereIsActive(1)
			->get();

		return (string) View::make('widgets.area.siteBottom', compact('advertising'))->render();
	}

}
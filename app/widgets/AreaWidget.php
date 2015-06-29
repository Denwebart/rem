<?php

class AreaWidget
{
	private $advertising = [];

	public function __construct() {
		$advertising = Advertising::whereIsActive(1)
			->get();

		foreach($advertising as $item) {
			$this->advertising[$item->area][] = $item;
		}
	}

	public function leftSidebar()
	{
		$advertising = $this->advertising[Advertising::AREA_LEFT_SIDEBAR];

		return (string) View::make('widgets.area.sidebar', compact('advertising'))->render();
	}

	public function rightSidebar()
	{
		$advertising = $this->advertising[Advertising::AREA_RIGHT_SIDEBAR];

		return (string) View::make('widgets.area.sidebar', compact('advertising'))->render();
	}

	public function contentTop()
	{
		$advertising = $this->advertising[Advertising::AREA_CONTENT_TOP];

		return (string) View::make('widgets.area.content', compact('advertising'))->render();
	}

	public function contentMiddle()
	{
		$advertising = $this->advertising[Advertising::AREA_CONTENT_MIDDLE];

		return (string) View::make('widgets.area.content', compact('advertising'))->render();
	}

	public function contentBottom()
	{
		$advertising = $this->advertising[Advertising::AREA_CONTENT_BOTTOM];

		return (string) View::make('widgets.area.content', compact('advertising'))->render();
	}

	public function siteBottom()
	{
		$advertising = $this->advertising[Advertising::AREA_SITE_BOTTOM];

		return (string) View::make('widgets.area.site', compact('advertising'))->render();
	}

}
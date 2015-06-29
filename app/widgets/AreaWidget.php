<?php

class AreaWidget
{
	private $advertising = [];

	public function __construct() {
		$access = Auth::check() ? Advertising::ACCESS_FOR_REGISTERED : Advertising::ACCESS_FOR_GUEST;
		$advertising = Advertising::whereIsActive(1)
			->whereIn('access', [Advertising::ACCESS_FOR_ALL, $access])
			->get();

		foreach($advertising as $item) {
			$this->advertising[$item->area][] = $item;
		}
	}

	public function leftSidebar()
	{
		$advertising = isset($this->advertising[Advertising::AREA_LEFT_SIDEBAR])
			? $this->advertising[Advertising::AREA_LEFT_SIDEBAR] : [];

		return (string) View::make('widgets.area.sidebar', compact('advertising'))->render();
	}

	public function rightSidebar()
	{
		$advertising = isset($this->advertising[Advertising::AREA_RIGHT_SIDEBAR])
			? $this->advertising[Advertising::AREA_RIGHT_SIDEBAR] : [];

		return (string) View::make('widgets.area.sidebar', compact('advertising'))->render();
	}

	public function contentTop()
	{
		$advertising = isset($this->advertising[Advertising::AREA_CONTENT_TOP])
			? $this->advertising[Advertising::AREA_CONTENT_TOP] : [];

		return (string) View::make('widgets.area.content', compact('advertising'))->render();
	}

	public function contentMiddle()
	{
		$advertising = isset($this->advertising[Advertising::AREA_CONTENT_MIDDLE])
			? $this->advertising[Advertising::AREA_CONTENT_MIDDLE] : [];

		return (string) View::make('widgets.area.content', compact('advertising'))->render();
	}

	public function contentBottom()
	{
		$advertising = isset($this->advertising[Advertising::AREA_CONTENT_BOTTOM])
			? $this->advertising[Advertising::AREA_CONTENT_BOTTOM] : [];

		return (string) View::make('widgets.area.content', compact('advertising'))->render();
	}

	public function siteBottom()
	{
		$advertising = isset($this->advertising[Advertising::AREA_SITE_BOTTOM])
			? $this->advertising[Advertising::AREA_SITE_BOTTOM] : [];

		return (string) View::make('widgets.area.site', compact('advertising'))->render();
	}

}
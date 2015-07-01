<?php

class AreaWidget
{
	private $advertising = [];

	public function __construct() {
		$access = Auth::check() ? Advertising::ACCESS_FOR_REGISTERED : Advertising::ACCESS_FOR_GUEST;

		$advertising = Advertising::whereIsActive(1)
			->whereIn('access', [Advertising::ACCESS_FOR_ALL, $access])
			->orderBy('position', 'ASC')
			->get();

		if(Auth::check()) {
			if(Auth::user()->isAdmin()) {
				$advertising = Advertising::orderBy('position', 'ASC')->get();
			}
		}

		foreach($advertising as $item) {
			$this->advertising[$item->area][] = $item;
		}
	}

	public function leftSidebar()
	{
		$area = Advertising::AREA_LEFT_SIDEBAR;
		$advertising = isset($this->advertising[$area])
			? $this->advertising[$area] : [];

		return (string) View::make('widgets.area.sidebar', compact('advertising', 'area'))->render();
	}

	public function rightSidebar()
	{
		$area = Advertising::AREA_RIGHT_SIDEBAR;
		$advertising = isset($this->advertising[$area])
			? $this->advertising[$area] : [];

		return (string) View::make('widgets.area.sidebar', compact('advertising', 'area'))->render();
	}

	public function contentTop()
	{
		$area = Advertising::AREA_CONTENT_TOP;
		$advertising = isset($this->advertising[$area])
			? $this->advertising[$area] : [];

		return (string) View::make('widgets.area.content', compact('advertising', 'area'))->render();
	}

	public function contentMiddle()
	{
		$area = Advertising::AREA_CONTENT_MIDDLE;
		$advertising = isset($this->advertising[$area])
			? $this->advertising[$area] : [];

		return (string) View::make('widgets.area.content', compact('advertising', 'area'))->render();
	}

	public function contentBottom()
	{
		$area = Advertising::AREA_CONTENT_BOTTOM;
		$advertising = isset($this->advertising[$area])
			? $this->advertising[$area] : [];

		return (string) View::make('widgets.area.content', compact('advertising', 'area'))->render();
	}

	public function siteBottom()
	{
		$area = Advertising::AREA_SITE_BOTTOM;
		$advertising = isset($this->advertising[$area])
			? $this->advertising[$area] : [];

		return (string) View::make('widgets.area.site', compact('advertising', 'area'))->render();
	}

}
<?php

class AreaWidget
{

	public function leftSidebar() {
		return (string) View::make('widgets.area.leftSidebar')->render();
	}

	public function rightSidebar() {
		return (string) View::make('widgets.area.rightSidebar')->render();
	}

	public function contentTop() {
		return (string) View::make('widgets.area.contentTop')->render();
	}

	public function contentMiddle() {
		return (string) View::make('widgets.area.contentMiddle')->render();
	}

	public function contentBottom() {
		return (string) View::make('widgets.area.contentBottom')->render();
	}

	public function siteBottom() {
		return (string) View::make('widgets.area.siteBottom')->render();
	}

}
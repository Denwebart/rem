<?php

class AdminController extends \BaseController
{
	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	public function index()
	{
		return View::make('admin::index');
	}
}
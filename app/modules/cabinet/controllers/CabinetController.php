<?php

class CabinetController extends \BaseController
{
	public function index()
	{
		return View::make('cabinet::index');
	}
}
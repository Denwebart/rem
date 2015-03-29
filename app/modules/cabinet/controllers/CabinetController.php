<?php

class CabinetController extends \BaseController
{
	public function index()
	{
		$users = User::whereIsActive(1)->paginate(10);

		return View::make('cabinet::index', compact('users'));
	}
}
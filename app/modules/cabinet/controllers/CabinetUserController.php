<?php


class CabinetUserController extends \BaseController
{
	public function index($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.index');
	}
}
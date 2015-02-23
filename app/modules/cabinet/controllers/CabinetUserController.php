<?php


class CabinetUserController extends \BaseController
{
	public function index($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.index');
	}

	public function edit($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.edit');
	}

	public function gallery($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.gallery');
	}

	public function questions($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.questions');
	}

	public function comments($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.comments');
	}

	public function messages($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.messages');
	}

	public function friends($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.friends');
	}
}
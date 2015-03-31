<?php

class CabinetController extends \BaseController
{
	public function index()
	{
		$users = User::whereIsActive(1)->paginate(10);

		$name = trim(Input::get('name'));

		if($name) {
			$users = User::select([DB::raw('*, CONCAT(firstname, " ", lastname) AS fullname')])
				->whereIsActive(1)
				->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE', "$name%")
				->orWhere(DB::raw('CONCAT(lastname, " ", firstname)'), 'LIKE', "$name%")
				->orWhere('login', 'like', "$name%")
				->paginate(10);
		}

		return View::make('cabinet::index', compact('users'))->with('name', $name);
	}

//	public function search()
//	{
//		$name = trim(Input::get('name'));
//
//		$users = User::select([DB::raw('*, CONCAT(firstname, " ", lastname) AS fullname')])
//			->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE', "$name%")
//			->orWhere(DB::raw('CONCAT(lastname, " ", firstname)'), 'LIKE', "$name%")
//			->orWhere('login', 'like', "$name%")
//			->paginate(10);
//
//		return View::make('cabinet::search', compact('users'))->with('name', $name);
//	}
}
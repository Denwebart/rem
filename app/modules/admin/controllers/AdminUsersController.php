<?php

class AdminUsersController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of users
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			if ($sortBy == 'fullname') {
				$users = User::orderBy('firstname', $direction)
					->orderBy('lastname', $direction)
					->paginate(10);
			} else {
				$users = User::orderBy($sortBy, $direction)
					->paginate(10);
			}
		} else {
			$users = User::orderBy('role', 'ASC')
				->paginate(10);
		}

		return View::make('admin::users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new user
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin::users.create');
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), User::$rules['create']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		User::create($data);

		return Redirect::route('admin.users.index');
	}

	/**
	 * Display the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::findOrFail($id);

		return View::make('admin::users.show', compact('user'));
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);

		return View::make('admin::users.edit', compact('user'));
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = User::findOrFail($id);

		$validator = Validator::make($data = Input::all(), User::$rules['edit']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$user->update($data);

		return Redirect::route('admin.users.index');
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		User::destroy($id);

		return Redirect::route('admin.users.index');
	}

	public function changeRole($id)
	{
		if(Request::ajax()) {
			$role = Input::get('role');

			$user = User::find($id);
			$user->role = $role;
			if($user->save()) {
				return Response::json(array(
					'success' => true,
				));
			}
		}
	}

	/**
	 * Display a listing of banned users
	 *
	 * @return Response
	 */
	public function banned()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			if ($sortBy == 'fullname') {
				$users = User::orderBy('firstname', $direction)
					->orderBy('lastname', $direction)
					->whereIsBanned(1)
					->paginate(10);
			} else {
				$users = User::orderBy($sortBy, $direction)
					->whereIsBanned(1)
					->paginate(10);
			}
		} else {
			$users = User::orderBy('role', 'ASC')
				->whereIsBanned(1)
				->paginate(10);
		}

		return View::make('admin::users.banned', compact('users'));
	}

	/**
	 * Забанить пользователя
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function ban($id)
	{
		if(Request::ajax()) {
			$user = User::find($id);
			if(!$user->isAdmin()) {
				$user->is_banned = 1;
				if($user->save()) {
					return Response::json(array(
						'success' => true,
						'message' => 'Пользователь забанен.'
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Администратора нельзя забанить.'
				));
			}
		}
	}

	/**
	 * Разбанить пользователя
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function unban($id)
	{
		if(Request::ajax()) {
			$user = User::find($id);
			if(!$user->isAdmin()) {
				$user->is_banned = 0;
				if($user->save()) {
					return Response::json(array(
						'success' => true,
						'message' => 'Пользователь разбанен.'
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Администратора нельзя разбанить, так как нельзя и забанить.'
				));
			}
		}
	}

}

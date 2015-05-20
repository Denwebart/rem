<?php

class AdminHonorsController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of honors
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$honors = Honor::orderBy($sortBy, $direction)->with('users')->paginate(10);
		} else {
			$honors = Honor::orderBy('id', 'DESC')->with('users')->paginate(10);
		}

		return View::make('admin::honors.index', compact('honors'));
	}

	/**
	 * Display the specified honor.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$honor = Honor::with('users')->findOrFail($id);

		return View::make('admin::honors.show', compact('honor'));
	}

	/**
	 *
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function usersAutocomplete($honorId) {
		$term = Input::get('term');

		$resultWithLogin = User::whereIsActive(1)
			->where(function ($query) use($honorId) {
				$query->whereDoesntHave('honors')
					->orWhereHas('honors', function($query) use($honorId) {
						$query->where('honor_id', '!=', $honorId);
					});
			})
			->where('login', 'like', "$term%")
			->lists('login', 'id');

		$resultWithFullName = User::whereIsActive(1)
			->where(function ($query) use($honorId) {
				$query->whereDoesntHave('honors')
					->orWhereHas('honors', function($query) use($honorId) {
						$query->where('honor_id', '!=', $honorId);
					});
			})
			->select([DB::raw('*, CONCAT(firstname, " ", lastname) AS fullname')])
			->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE', "$term%")
			->orWhere(DB::raw('CONCAT(lastname, " ", firstname)'), 'LIKE', "$term%")
			->lists('fullname', 'id');

		$result = array_merge($resultWithLogin, $resultWithFullName);

		return Response::json($result);
	}

	public function toReward()
	{
		if(Request::ajax()) {
			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);

			$user = User::select([DB::raw('id, login, firstname, lastname, CONCAT(firstname, " ", lastname) AS fullname')])
				->whereLogin($formFields['name'])
				->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), '=', $formFields['name'])
				->first();

			if(is_null($user)) {
				return Response::json(array(
					'userNotFound' => true,
				));
			}

			$data = array(
				'user_id' => $user->id,
				'honor_id' => $formFields['honor_id'],
			);

			$userHonor = UserHonor::whereUserId($user->id)->whereHonorId($formFields['honor_id'])->first();

			if (!$userHonor) {
				if(UserHonor::create($data)){
					$userRowView = 'admin::honors.userRow';
					return Response::json(array(
						'success' => true,
						'userRowHtml' => (string) View::make($userRowView, compact('user'))->render()
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
				));
			}
		}
	}

	/**
	 * Show the form for creating a new page
	 *
	 * @return Response
	 */
	public function create()
	{
		$honor = new Honor();

		return View::make('admin::honors.create', compact('honor'));
	}

	/**
	 * Store a newly created page in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$validator = Validator::make($data, Honor::$rules);

		if($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['image'])){
			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . (new Honor)->getTable() . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			$image->save($imagePath . $fileName);

			$data['image'] = $fileName;
		}
		// загрузка изображения

		Honor::create($data);

		return Redirect::route('admin.honors.index');
	}

	/**
	 * Show the form for editing the specified honor.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$honor = Honor::find($id);

		return View::make('admin::honors.edit', compact('honor'));
	}

	/**
	 * Update the specified honor in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$honor = Honor::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Honor::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['image'])){
			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . (new Honor)->getTable() . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			$image->save($imagePath . $fileName);

			$data['image'] = $fileName;
		}
		// загрузка изображения

		$honor->update($data);

		return Redirect::route('admin.honors.index');
	}

	/**
	 * Remove the specified honor from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Honor::destroy($id);

		return Redirect::route('admin.honors.index');
	}

}

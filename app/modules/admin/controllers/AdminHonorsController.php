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
        $searchQuery = Request::get('query');

        $query = new Honor();
        $query = $query->with('users.userHonors');
        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($q) use($searchQuery) {
                $q->where(DB::raw('LOWER(title)'), 'LIKE', "%$searchQuery%")
                    ->orWhere(DB::raw('LOWER(alias)'), 'LIKE', "%$searchQuery%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('id', 'ASC');
        }

        $honors = $query->paginate(10);

		return View::make('admin::honors.index', compact('honors'));
	}

    /**
     * Поиск награды
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search() {
        if(Request::ajax()) {
            $inputData = Request::get('searchData');
            parse_str($inputData, $data);

            $sortBy = isset($data['sortBy']) ? $data['sortBy'] : null;
            $direction = isset($data['direction']) ? $data['direction'] : null;
            $searchQuery = $data['query'];

            $query = new Honor();
            $query = $query->with('users.userHonors');
            if ($searchQuery) {
                $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($q) use($searchQuery) {
                    $q->where(DB::raw('LOWER(title)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(alias)'), 'LIKE', "%$searchQuery%");
                });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('id', 'ASC');
            }

            $honors = $query->paginate(10);
            $url = URL::route('admin.honors.index', $data);

            Session::set('user.url', $url);

            return Response::json([
                'success' => true,
                'url' => $url,
                'honorsListHtmL' => (string) View::make('admin::honors.list', compact('honors', 'url'))->render(),
                'honorsPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $honors)->render(),
                'honorsCountHtmL' => (string) View::make('admin::parts.count')->with('models', $honors)->render(),
            ]);
        }
    }

	/**
	 * Display the specified honor.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$honor = Honor::with('users.userHonors')->findOrFail($id);

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

	/**
	 * Награждение пользователя
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function toReward()
	{
		if(Request::ajax()) {
			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);

			$user = User::select([DB::raw('id, login, firstname, lastname, avatar, CONCAT(firstname, " ", lastname) AS fullname')])
				->whereLogin($formFields['name'])
				->orWhere(DB::raw('CONCAT(firstname, " ", lastname)'), '=', $formFields['name'])
				->first();

			$honor = Honor::select()->whereId($formFields['honor_id'])->whereNull('key')->first();

			if(is_null($user)) {
				return Response::json(array(
					'fail' => true,
					'message' => 'Такого пользователя нет'
				));
			}
			if(is_null($honor)) {
				return Response::json(array(
					'fail' => true,
					'message' => 'Такой награды нет'
				));
			}

			$data = array(
				'user_id' => $user->id,
				'honor_id' => $honor->id,
			);

			$userHonor = UserHonor::whereUserId($user->id)->whereHonorId($formFields['honor_id'])->first();

			if (!$userHonor) {
				if(UserHonor::create($data)){
					$userRowView = 'admin::honors.userRow';
					return Response::json(array(
						'success' => true,
						'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Пользователь награжден'])->render(),
						'userRowHtml' => (string) View::make($userRowView, compact('user', 'honor'))->render()
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.danger', ['siteMessage' => 'У пользователя уже есть эта награда'])->render(),
				));
			}
		}
	}

	/**
	 * Снаятие награды
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function removeReward()
	{
		if(Request::ajax()) {
			$userHonor = UserHonor::whereUserId(Input::get('user_id'))
				->whereHonorId(Input::get('honor_id'))
				->delete();
			if ($userHonor) {
				return Response::json(array(
					'success' => true,
					'message' => 'Награда снята.'
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Не удалось снять награду.'
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

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.honors.index');

		return View::make('admin::honors.create', compact('honor', 'backUrl'));
	}

	/**
	 * Store a newly created page in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$data['description'] = StringHelper::nofollowLinks($data['description']);

		$validator = Validator::make($data, Honor::$rules);

		if($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$honor = Honor::create($data);

		// загрузка изображения
		$honor->image = $honor->setImage($data['image']);
        $honor->description = $honor->saveEditorImages($data['tempPath']);
		$honor->save();

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.honors.index');
        return Redirect::to($backUrl);
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

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.honors.index');

		return View::make('admin::honors.edit', compact('honor', 'backUrl'));
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
		$data = Input::all();
		$data['description'] = StringHelper::nofollowLinks($data['description']);

		$validator = Validator::make($data, $honor->getValidationRules());

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		$data['image'] = $honor->setImage($data['image']);

		$honor->fill($data);
        $honor->description = $honor->saveEditorImages($data['tempPath']);
        $honor->save();

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.honors.index');
        return Redirect::to($backUrl);
	}

	/**
	 * Remove the specified honor from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$honor = Honor::find($id);

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.honors.index');

		if(is_null($honor->key)) {
			$honor->delete();
		} else {
			return Redirect::to($backUrl)->with('warningMessage', 'Эту награду нельзя удалить.');
		}

		return Redirect::to($backUrl);;
	}

	/**
	 * Удаление изображения
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteImage($id) {
		if(Request::ajax())
		{
			$honor = Honor::findOrFail($id);
			$imagePath = public_path() . '/uploads/' . $honor->getTable() . '/' . $honor->image;

			// delete old image
			if(File::exists($imagePath)) {
				File::delete($imagePath);
			}

			$honor->image = null;
			$honor->save();

			return Response::json([
				'success' => true,
			]);
		}
	}

}

<?php

class CabinetController extends \BaseController
{
	public function __construct()
	{
		parent::__construct();

		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SEARCH]);
		View::share('areaWidget', $areaWidget);

		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}

		$this->afterFilter(function()
		{
			Session::put('user.urlPrevious', URL::current());
		});
	}

	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::has('direction') ? Request::get('direction') : 'desc';

		$relations = ['publishedArticles', 'publishedQuestions', 'publishedComments', 'publishedAnswers', 'userHonors.honor', 'honors'];
		$name = trim(Input::get('name'));

		$query = new User;
		$query = $query->where('users.is_active', '=', 1);
		$query = $query->with($relations);

		if($name) {
			$name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $name))));
			$query = $query->where(function($q) use ($name) {
				$q->where(DB::raw('LOWER(CONCAT(users.login, " ", users.firstname, " ", users.lastname))'), 'LIKE', "$name%")
					->orWhere(DB::raw('LOWER(CONCAT(users.login, " ", users.lastname, " ", users.firstname))'), 'LIKE', "$name%")
					->orWhere(DB::raw('LOWER(CONCAT(users.lastname, " ", users.firstname, " ", users.login))'), 'LIKE', "$name%")
					->orWhere(DB::raw('LOWER(CONCAT(users.firstname, " ", users.lastname, " ", users.login))'), 'LIKE', "$name%")
					->orWhere(DB::raw('LOWER(CONCAT(users.firstname, " ", users.login, " ", users.lastname))'), 'LIKE', "$name%")
					->orWhere(DB::raw('LOWER(CONCAT(users.lastname, " ", users.login, " ", users.firstname))'), 'LIKE', "$name%")
					->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$name%");
			});
		}

		$is_online = (Input::get('is_online') == 1) ? 1 : 0;
		if($is_online) {
			$query = $query->whereIsOnline(1);
			$query = $query->where('last_activity', '>', \Carbon\Carbon::now()->subMinutes(2));
		}

		if ($sortBy && $direction) {
			if(in_array($sortBy, $relations)) {
				if($direction == 'asc') {
					$users = $query->get()->sortBy(function($user) use($sortBy) {
							return $user->$sortBy->count();
						});
				} else {
					$users = $query->get()->sortBy(function($user) use($sortBy) {
						return $user->$sortBy->count();
					})->reverse();
				}
			} else {
				$query = $query->orderBy($sortBy, $direction);
				$users = $query->paginate(10);
			}
		} else {
			$query = $query->orderBy('users.role', 'ASC')
				->orderBy('users.created_at', 'ASC');
//			$users = $query->toSql();
//			dd(date('Y-m-d H:i:s'), $users);
			$users = $query->paginate(10);
		}

		return View::make('cabinet::index', compact('users'))->with('name', $name)->with('is_online', $is_online);
	}

	public function autocomplete() {
		$term = Input::get('term');

		$resultWithLogin = User::whereIsActive(1)
			->where('login', 'like', "$term%")
			->lists('login', 'id');

		$resultWithFullName = User::whereIsActive(1)
			->select([DB::raw('*, CONCAT(firstname, " ", lastname) AS fullname')])
			->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE', "$term%")
			->orWhere(DB::raw('CONCAT(lastname, " ", firstname)'), 'LIKE', "$term%")
			->lists('fullname', 'id');

		$result = array_merge($resultWithLogin, $resultWithFullName);

		return Response::json($result);
	}

	/**
	 * Правила получения наград.
	 *
	 * @return \Illuminate\View\View
	 */
	public function honors()
	{
		$page = Page::whereAlias('honors')->firstOrFail();
		$page->setViews();

		View::share('page', $page);
		return View::make('cabinet::honors');
	}

	/**
	 * Информация о награде.
	 *
	 * @param $alias
	 * @return \Illuminate\View\View
	 */
	public function honor($alias)
	{
		$honor = Honor::whereAlias($alias)->firstOrFail();

		return View::make('cabinet::honor', compact('honor'));
	}

}
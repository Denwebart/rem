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
		$interval = Request::has('interval') ? Request::get('interval') : User::INTERVAL_ALL_TIMES;
		$month = Request::has('month') ? Request::get('month') : date('n');
		$year = Request::has('year') ? Request::get('year') : date('Y');

		$relations = ['publishedArticles', 'publishedQuestions', 'publishedComments', 'publishedAnswers', 'honors'];
		$name = trim(Input::get('name'));

		$query = new User;
		$query = $query->whereIsActive(1);
		$query = $query->with($relations);

		if($name) {
			$query = $query->select([DB::raw('*, CONCAT(firstname, " ", lastname) AS fullname')])
				->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE', "$name%")
				->orWhere(DB::raw('CONCAT(lastname, " ", firstname)'), 'LIKE', "$name%")
				->orWhere('login', 'like', "$name%");
		}

//		if(User::INTERVAL_MONTH == $interval) {
//			$query = $query->where('created_at', '>', date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year)));
//		}
//		if(User::INTERVAL_YEAR == $interval) {
//			$query = $query->where('created_at', '>', date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, $year)));
//		}

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
			$query = $query->orderBy('role', 'ASC')
				->orderBy('created_at', 'ASC');
			$users = $query->paginate(10);
		}

		return View::make('cabinet::index', compact('users'))->with('name', $name);
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
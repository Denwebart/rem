<?php

class CabinetController extends \BaseController
{
	public function __construct()
	{
		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SEARCH]);
		View::share('areaWidget', $areaWidget);

		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction') ? Request::get('direction') : 'desc';

		$relations = ['publishedArticles', 'publishedQuestions', 'publishedComments', 'publishedAnswers', 'honors'];
		if ($sortBy && $direction) {
			if(in_array($sortBy, $relations)) {
				if($direction == 'asc') {
					$users = User::whereIsActive(1)
						->with($relations)
						->get()->sortBy(function($user) use($sortBy) {
							return $user->$sortBy->count();
						});
				} else {
					$users = User::whereIsActive(1)
						->with($relations)
						->get()->sortBy(function($user) use($sortBy) {
							return $user->$sortBy->count();
						})->reverse();
				}
			} else {
				$users = User::whereIsActive(1)
					->with($relations)
					->orderBy($sortBy, $direction)
					->paginate(10);
			}
		} else {
			$users = User::whereIsActive(1)
				->with($relations)
				->orderBy('role', 'ASC')
				->orderBy('created_at', 'ASC')
				->paginate(10);
		}

		$name = trim(Input::get('name'));

		if($name) {
			$users = User::select([DB::raw('*, CONCAT(firstname, " ", lastname) AS fullname')])
				->whereIsActive(1)
				->where(DB::raw('CONCAT(firstname, " ", lastname)'), 'LIKE', "$name%")
				->orWhere(DB::raw('CONCAT(lastname, " ", firstname)'), 'LIKE', "$name%")
				->orWhere('login', 'like', "$name%")
				->with($relations)
				->paginate(10);
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
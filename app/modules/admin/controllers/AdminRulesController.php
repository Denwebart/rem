<?php

class AdminRulesController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of rules.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$rules = Rule::orderBy($sortBy, $direction)->paginate(10);
		} else {
			$rules = Rule::orderBy('position', 'ASC')->paginate(10);
		}

		return View::make('admin::rules.index', compact('rules'));
	}

	/**
	 * Show the form for creating a new rule
	 *
	 * @return Response
	 */
	public function create()
	{
		$rule = new Rule();

		return View::make('admin::rules.create', compact('rule'));
	}

	/**
	 * Store a newly created rule in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();

		$validator = Validator::make($data, Rule::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Rule::create($data);

		return Redirect::route('admin.rules.index');
	}

	/**
	 * Show the form for editing the specified rule.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$rule = Rule::find($id);

		return View::make('admin::rules.edit', compact('rule'));
	}

	/**
	 * Update the specified rule in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rule = Rule::findOrFail($id);

		$validator = Validator::make($data = Input::all(), rule::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$rule->update($data);

		return Redirect::route('admin.rules.index');
	}

	/**
	 * Remove the specified rule from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Rule::destroy($id);

		return Redirect::route('admin.rules.index');
	}

}

<?php

class AdminАdvertisingController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of advertisings.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$advertising = Advertising::orderBy($sortBy, $direction)->paginate(10);
		} else {
			$advertising = Advertising::orderBy('created_at', 'DESC')->paginate(10);
		}

		return View::make('admin::advertising.index', compact('advertising'));
	}

	/**
	 * Display the specified advertising.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$advertising = Advertising::findOrFail($id);

		return View::make('admin::advertising.show', compact('advertising'));
	}

	/**
	 * Show the form for editing the specified advertising.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$advertising = Advertising::find($id);

		return View::make('admin::advertising.edit', compact('advertising'));
	}

	/**
	 * Update the specified advertising in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$advertising = Advertising::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Advertising::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$advertising->update($data);

		return Redirect::route('admin.advertising.index');
	}

}

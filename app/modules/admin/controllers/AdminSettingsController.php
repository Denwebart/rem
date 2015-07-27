<?php

class AdminSettingsController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of settings.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$settings = Setting::orderBy($sortBy, $direction)->paginate(10);
		} else {
			$settings = Setting::orderBy('created_at', 'DESC')->paginate(10);
		}

		return View::make('admin::settings.index', compact('settings'));
	}

	/**
	 * Display the specified setting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$setting = Setting::findOrFail($id);

		return View::make('admin::settings.show', compact('setting'));
	}

	/**
	 * Show the form for editing the specified setting.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$setting = Setting::find($id);

		return View::make('admin::settings.edit', compact('setting'));
	}

	/**
	 * Update the specified setting in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$setting = Setting::findOrFail($id);

		$data = Input::all();
		if(is_array($data['value'])) {
			$data['value'] = implode($data['value'], ', ');
		}

		$validator = Validator::make($data, Setting::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$setting->update($data);

		return Redirect::route('admin.settings.index');
	}

}

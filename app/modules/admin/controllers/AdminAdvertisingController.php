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
	 * Show the form for creating a new advertising
	 *
	 * @return Response
	 */
	public function create()
	{
		$advertising = new Advertising();

		$backUrl = Request::get('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.advertising.index');

		return View::make('admin::advertising.create', compact('advertising', 'backUrl'));
	}

	/**
	 * Store a newly created advertising in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();

		$validator = Validator::make($data, Advertising::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Advertising::create($data);

		return Redirect::route('admin.advertising.index');
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

		$backUrl = Request::get('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.advertising.index');

		return View::make('admin::advertising.edit', compact('advertising', 'backUrl'));
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

		return Redirect::to(Input::get('backUrl'));
	}

	/**
	 * Remove the specified advertising from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Advertising::destroy($id);

		return Redirect::back();
	}

	/**
	 * Включение/выключение рекламного блока (ajax)
	 *
	 * @param $advertisingId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function changeActiveStatus($advertisingId)
	{
		if(Request::ajax()) {

			$advertising = Advertising::find($advertisingId);
			$advertising->is_active = !Input::get('is_active') ? 1 : 0;
			if($advertising->save()) {
				return Response::json(array(
					'success' => true,
					'isActive' => $advertising->is_active,
					'message' => ($advertising->is_active)
						? 'Рекламный блок включен.'
						: 'Рекламный блок выключен.',
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Что-то пошло не так.'
				));
			}
		}
	}

}

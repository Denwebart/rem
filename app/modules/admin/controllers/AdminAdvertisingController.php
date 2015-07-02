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
		if(!Request::has('id')) {
			$sortBy = Request::get('sortBy');
			$direction = Request::get('direction');
			if ($sortBy && $direction) {
				$advertising = Advertising::orderBy($sortBy, $direction)->paginate(10);
			} else {
				$advertising = Advertising::orderBy('created_at', 'DESC')->paginate(10);
			}
		} else {
			$advertising = Advertising::whereId(Request::get('id'))->paginate(10);
		}

		return View::make('admin::advertising.index', compact('advertising'));
	}

	/**
	 * Show the form for creating a new advertising
	 *
	 * @return Response
	 */
	public function create()
	{
		$advertising = new Advertising();
		$advertising->area = Request::get('area');

		$backUrl = Request::has('backUrl')
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
		$data['code'] = (Input::get('type') == Advertising::TYPE_WIDGET)
			? Input::get('code-widget')
			: Input::get('code-advertising');

		$validator = Validator::make($data, Advertising::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$advertising = Advertising::create($data);
		AdvertisingPage::add($advertising, Input::get('pages'));

		return Redirect::to(Input::get('backUrl'));
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

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.advertising.index');

		$pages = [];
		foreach ($advertising->pagesTypes as $advertisingPage) {
			$pages[$advertisingPage->page_type] = $advertisingPage->page_type;
		}
		return View::make('admin::advertising.edit', compact('advertising', 'backUrl', 'pages'));
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

		$data = Input::all();

		$data['code'] = (Input::get('type') == Advertising::TYPE_WIDGET)
			? Input::get('code-widget')
			: Input::get('code-advertising');

		$validator = Validator::make($data, Advertising::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$advertising->update($data);
		AdvertisingPage::add($advertising, Input::get('pages'));

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

		return Request::has('backUrl')
			? Redirect::to(urldecode(Request::get('backUrl')))
			: Redirect::back();
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

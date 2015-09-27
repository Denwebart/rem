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
            $area = Request::get('area');
            $searchQuery = Request::get('searchQuery');

            $query = new Advertising();
            $query = $query->with('pagesTypes');
            if ($area) {
                $query = $query->whereArea($area);
            }
            if ($searchQuery) {
                $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                    ->orWhere(DB::raw('LOWER(meta_title)'), 'LIKE', "%$title%");
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $advertising = $query->paginate(10);
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
     * Поиск ajax
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search() {
        if(Request::ajax()) {
            $inputData = Request::get('searchData');
            parse_str($inputData, $data);

            $sortBy = isset($data['sortBy']) ? $data['sortBy'] : null;
            $direction = isset($data['direction']) ? $data['direction'] : null;
            $area = $data['area'];
            $searchQuery = $data['query'];

            $query = new Advertising();
            $query = $query->with('pagesTypes');
            if ($area) {
                $query = $query->whereArea($area);
            }
            if ($searchQuery) {
                $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                    ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$title%");
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $advertising = $query->paginate(10);

            return Response::json([
                'success' => true,
                'url' => URL::route('admin.advertising.index', $data),
                'advertisingListHtmL' => (string) View::make('admin::advertising.list', compact('advertising'))->render(),
                'advertisingPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $advertising)->render(),
                'advertisingCountHtmL' => (string) View::make('admin::parts.count')->with('models', $advertising)->render(),
            ]);
        }
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
						? (string) View::make('widgets.siteMessages.info', ['siteMessage' => 'Рекламный блок включен.'])
						: (string) View::make('widgets.siteMessages.info', ['siteMessage' => 'Рекламный блок выключен.']),
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.danger', ['siteMessage' => 'Что-то пошло не так.'])
				));
			}
		}
	}

}

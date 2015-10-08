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
        $searchQuery = Request::get('query');

        $query = new Setting();

        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($q) use($searchQuery) {
                $q->where(DB::raw('LOWER(`key`)'), 'LIKE', "%$searchQuery%")
                    ->orWhere(DB::raw('LOWER(title)'), 'LIKE', "%$searchQuery%")
                    ->orWhere(DB::raw('LOWER(category)'), 'LIKE', "%$searchQuery%")
                    ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$searchQuery%")
                    ->orWhere(DB::raw('LOWER(value)'), 'LIKE', "%$searchQuery%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('id', 'ASC');
        }

        $settings = $query->paginate(10);

		return View::make('admin::settings.index', compact('settings'));
	}

    /**
     * Поиск настройки
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

            $query = new Setting();

            if ($searchQuery) {
                $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($q) use($searchQuery) {
                    $q->where(DB::raw('LOWER(`key`)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(title)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(category)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(value)'), 'LIKE', "%$searchQuery%");
                });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('id', 'ASC');
            }

            $settings = $query->paginate(10);
            $url = URL::route('admin.settings.index', $data);

            return Response::json([
                'success' => true,
                'url' => $url,
                'settingsListHtmL' => (string) View::make('admin::settings.list', compact('settings', 'url'))->render(),
                'settingsPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $settings)->render(),
                'settingsCountHtmL' => (string) View::make('admin::parts.count')->with('models', $settings)->render(),
            ]);
        }
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

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.setting.index');

		return View::make('admin::settings.edit', compact('setting', 'backUrl'));
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

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.settings.index');
        return Redirect::to($backUrl);
	}

}

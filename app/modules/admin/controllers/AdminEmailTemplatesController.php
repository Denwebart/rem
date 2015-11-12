<?php

class AdminEmailTemplatesController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of notifications messages.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
        $searchQuery = Request::get('query');

        $query = new EmailTemplate();

        if ($searchQuery) {
	        $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($q) use($searchQuery) {
	            $q->where(DB::raw('LOWER(`key`)'), 'LIKE', "%$searchQuery%")
		            ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$searchQuery%")
		            ->orWhere(DB::raw('LOWER(subject)'), 'LIKE', "%$searchQuery%")
		            ->orWhere(DB::raw('LOWER(html)'), 'LIKE', "%$searchQuery%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('id', 'ASC');
        }

        $emailTemplates = $query->paginate(10);

		return View::make('admin::emailTemplates.index', compact('emailTemplates'));
	}

    /**
     * Поиск шаблона уведомления
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

            $query = new EmailTemplate();

            if ($searchQuery) {
	            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($q) use($searchQuery) {
                    $q->where(DB::raw('LOWER(`key`)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(subject)'), 'LIKE', "%$searchQuery%")
                        ->orWhere(DB::raw('LOWER(html)'), 'LIKE', "%$searchQuery%");
                });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('id', 'ASC');
            }

            $emailTemplates = $query->paginate(10);
            $url = URL::route('admin.emailTemplates.index', $data);

            return Response::json([
                'success' => true,
                'url' => $url,
                'listHtmL' => (string) View::make('admin::emailTemplates.list', compact('emailTemplates', 'url'))->render(),
                'paginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $emailTemplates)->render(),
                'countHtmL' => (string) View::make('admin::parts.count')->with('models', $emailTemplates)->render(),
            ]);
        }
    }

	/**
	 * Show the form for editing the specified notification message.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$emailTemplate = EmailTemplate::find($id);

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.emailTemplates.index');

		return View::make('admin::emailTemplates.edit', compact('emailTemplate', 'backUrl'));
	}

	/**
	 * Update the specified notification message in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$emailTemplate = EmailTemplate::findOrFail($id);

		$validator = Validator::make($data = Input::all(), EmailTemplate::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$emailTemplate->update($data);

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.emailTemplates.index');
        return Redirect::to($backUrl);
	}
}
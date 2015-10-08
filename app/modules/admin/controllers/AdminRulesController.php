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
        $searchQuery = Request::get('query');

        $query = new Rule();

        if ($searchQuery) {
            $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($q) use($title) {
                $q->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                    ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$title%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('position', 'ASC');
        }

        $rules = $query->paginate(10);

		return View::make('admin::rules.index', compact('rules'));
	}

    /**
     * Поиск правила
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

            $query = new Rule();

            if ($searchQuery) {
                $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($q) use($title) {
                    $q->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                        ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$title%");
                });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('position', 'ASC');
            }

            $rules = $query->paginate(10);
            $url = URL::route('admin.rules.index', $data);

            Session::set('user.url', $url);

            return Response::json([
                'success' => true,
                'url' => $url,
                'rulesListHtmL' => (string) View::make('admin::rules.list', compact('rules', 'url'))->render(),
                'rulesPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $rules)->render(),
                'rulesCountHtmL' => (string) View::make('admin::parts.count')->with('models', $rules)->render(),
            ]);
        }
    }

	/**
	 * Show the form for creating a new rule
	 *
	 * @return Response
	 */
	public function create()
	{
		$rule = new Rule();

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.pages.index');

		return View::make('admin::rules.create', compact('rule', 'backUrl'));
	}

	/**
	 * Store a newly created rule in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();

		$validator = Validator::make($data, Rule::rules());

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$rule = Rule::create($data);
        $rule->description = $rule->saveEditorImages($data['tempPath']);
        $rule->save();

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.rules.index');
        return Redirect::to($backUrl);
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

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.pages.index');

		return View::make('admin::rules.edit', compact('rule', 'backUrl'));
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

		$validator = Validator::make($data = Input::all(), Rule::rules($rule->id));

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$rule->fill($data);
        $rule->description = $rule->saveEditorImages($data['tempPath']);
        $rule->save();

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.rules.index');
        return Redirect::to($backUrl);
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

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.rules.index');
        return Redirect::to($backUrl);
	}

}

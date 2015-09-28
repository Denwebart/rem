<?php

class AdminNotificationsMessagesController extends \BaseController {

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

        $query = new NotificationMessage();

        if ($searchQuery) {
            $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($q) use($title) {
                $q->where(DB::raw('LOWER(message)'), 'LIKE', "%$title%")
                    ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$title%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('id', 'ASC');
        }

        $notificationsMessages = $query->paginate(10);

		return View::make('admin::notificationsMessages.index', compact('notificationsMessages'));
	}

    /**
     * Поиск
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

            $query = new NotificationMessage();

            if ($searchQuery) {
                $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($q) use($title) {
                    $q->where(DB::raw('LOWER(message)'), 'LIKE', "%$title%")
                        ->orWhere(DB::raw('LOWER(description)'), 'LIKE', "%$title%");
                });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('id', 'ASC');
            }

            $notificationsMessages = $query->paginate(10);

            return Response::json([
                'success' => true,
                'url' => URL::route('admin.notificationsMessages.index', $data),
                'notificationsMessagesListHtmL' => (string) View::make('admin::notificationsMessages.list', compact('notificationsMessages'))->render(),
                'notificationsMessagesPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $notificationsMessages)->render(),
                'notificationsMessagesCountHtmL' => (string) View::make('admin::parts.count')->with('models', $notificationsMessages)->render(),
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
		$notificationMessage = NotificationMessage::find($id);

		return View::make('admin::notificationsMessages.edit', compact('notificationMessage'));
	}

	/**
	 * Update the specified notification message in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$notificationMessage = NotificationMessage::findOrFail($id);

		$validator = Validator::make($data = Input::all(), NotificationMessage::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$notificationMessage->update($data);

		return Redirect::route('admin.notificationsMessages.index');
	}
}
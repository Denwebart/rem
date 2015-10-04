<?php

class AdminLettersController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of letters
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
        $author =  Request::get('author');
        $searchQuery = Request::get('query');

        $query = new Letter;
        $query = $query->whereNull('deleted_at');
        $query = $query->with('ip', 'user');

        if ($author) {
            $name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ.@_ \-\']+$/u%', '', $author))));
            $query = $query->where(function($qu) use ($name) {
                $qu->whereHas('user', function($q) use ($name) {
                    $q->where(function($que) use ($name) {
                        $que->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$name%")
                            ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$name%");
                    });
                })
                ->orWhere(DB::raw('LOWER(user_name)'), 'LIKE', "$name%")
                ->orWhere(DB::raw('LOWER(user_email)'), 'LIKE', "$name%");
            });
        }
        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(DB::raw('LOWER(subject)'), 'LIKE', "%$searchQuery%")
                ->orWhere(DB::raw('LOWER(message)'), 'LIKE', "%$searchQuery%")
                ->orWhereHas('ip', function($q) use ($searchQuery) {
                    $q->where(DB::raw('LOWER(ip)'), 'LIKE', "%$searchQuery%");
                });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $letters = $query->paginate(10);

		return View::make('admin::letters.index', compact('letters'));
	}

    /**
     * Поиск писем
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search() {
        if(Request::ajax()) {
            $inputData = Request::get('searchData');
            parse_str($inputData, $data);

            $sortBy = isset($data['sortBy']) ? $data['sortBy'] : null;
            $direction = isset($data['direction']) ? $data['direction'] : null;
            $author = $data['author'];
            $searchQuery = $data['query'];

            $query = new Letter;
            if(Request::get('route') != 'trash') {
                $query = $query->whereNull('deleted_at');
            } else {
                $query = $query->whereNotNull('deleted_at');
            }
            $query = $query->with('ip', 'user');

            if ($author) {
                $name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ.@_ \-\']+$/u%', '', $author))));
                $query = $query->where(function($qu) use ($name) {
                    $qu->whereHas('user', function($q) use ($name) {
                        $q->where(function($que) use ($name) {
                            $que->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$name%")
                                ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$name%")
                                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$name%")
                                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$name%")
                                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$name%")
                                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$name%")
                                ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$name%")
                                ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$name%");
                        });
                    })
                    ->orWhere(DB::raw('LOWER(user_name)'), 'LIKE', "$name%")
                    ->orWhere(DB::raw('LOWER(user_email)'), 'LIKE', "$name%");
                });
            }
            if ($searchQuery) {
                $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(DB::raw('LOWER(subject)'), 'LIKE', "%$searchQuery%")
                    ->orWhere(DB::raw('LOWER(message)'), 'LIKE', "%$searchQuery%")
                    ->orWhereHas('ip', function($q) use ($searchQuery) {
                        $q->where(DB::raw('LOWER(ip)'), 'LIKE', "%$searchQuery%");
                    });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $letters = $query->paginate(10);
            if(Request::has('route')) {
                $letters->setBaseUrl('/admin/letters/' . Request::get('route'));
            } else {
                $letters->setBaseUrl('/admin/letters');
            }

            $view = Request::has('view') ? Request::get('view') : 'list';
            $route = Request::has('route') ? Request::get('route') : 'index';

            return Response::json([
                'success' => true,
                'url' => URL::route('admin.letters.' . $route, $data),
                'lettersListHtmL' => (string) View::make('admin::letters.' . $view, compact('letters'))->with('notFoundLetters', 'Ничего не найдено.')->render(),
                'lettersPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $letters)->render(),
                'lettersCountHtmL' => (string) View::make('admin::parts.count')->with('models', $letters)->render(),
            ]);
        }
    }

	/**
	 * Display a listing of deleted letters
	 *
	 * @return Response
	 */
	public function trash()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
        $author =  Request::get('author');
        $searchQuery = Request::get('query');

        $query = new Letter;
        $query = $query->whereNotNull('deleted_at');
        $query = $query->with('ip', 'user');

        if ($author) {
            $name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $author))));
            $query = $query->whereHas('user', function($q) use ($name) {
                $q->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$name%")
                    ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$name%")
                    ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$name%")
                    ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$name%")
                    ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$name%")
                    ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$name%")
                    ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$name%");
            })->orWhere(DB::raw('LOWER(user_name)'), 'LIKE', "$name%");
        }
        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(DB::raw('LOWER(subject)'), 'LIKE', "%$searchQuery%")
                ->orWhere(DB::raw('LOWER(message)'), 'LIKE', "%$searchQuery%")
                ->orWhereHas('ip', function($q) use ($searchQuery) {
                    $q->where(DB::raw('LOWER(ip)'), 'LIKE', "%$searchQuery%");
                });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $letters = $query->paginate(10);

		return View::make('admin::letters.trash', compact('letters'));
	}

	/**
	 * Display the specified letter.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$letter = Letter::findOrFail($id);
		$letter->read_at = date('Y:m:d H:i:s');
		$letter->save();

		return View::make('admin::letters.show', compact('letter'));
	}

	/**
	 * Remove the specified letter from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Letter::destroy($id);

		return Redirect::route('admin.letters.trash');
	}

	/**
	 * Перемещение письма в корзину (отметить как удаленное)
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function markAsDeleted($id)
	{
		$letter = Letter::findOrFail($id);
		$letter->deleted_at = date('Y:m:d H:i:s');
		$letter->save();

		return Redirect::route('admin.letters.index');
	}

	/**
	 * Перемещение письма из корзины во входящие
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function markAsNew($id)
	{
		$letter = Letter::findOrFail($id);
		$letter->deleted_at = null;
		$letter->save();

		return Redirect::route('admin.letters.trash');
	}

}

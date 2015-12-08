<?php

class AdminUsersController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of users
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		$searchQuery = Request::get('query');

        $query = new User();
        $query = $query->with('userHonors');
        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$searchQuery%")
                ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$searchQuery%")
                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$searchQuery%")
                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$searchQuery%")
                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$searchQuery%")
                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$searchQuery%")
                ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$searchQuery%")
                ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$searchQuery%");
        }

        if ($sortBy && $direction) {
            if ($sortBy == 'fullname') {
                $query = $query->orderBy('firstname', $direction)->orderBy('lastname', $direction);
            } else {
                $query = $query->orderBy($sortBy, $direction);
            }
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $users = $query->paginate(10);

		return View::make('admin::users.index', compact('users'));
	}

    /**
     * Поиск пользователей
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

            $query = new User();
            if(Request::get('route') == 'banned') {
                $query = $query->whereIsBanned(1);
            }
            $query = $query->with('userHonors');
            if ($searchQuery) {
                $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($q) use($searchQuery) {
                    $q->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$searchQuery%")
                        ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$searchQuery%")
                        ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$searchQuery%")
                        ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$searchQuery%")
                        ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$searchQuery%")
                        ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$searchQuery%")
                        ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$searchQuery%")
                        ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$searchQuery%");
                });
            }

            if ($sortBy && $direction) {
                if ($sortBy == 'fullname') {
                    $query = $query->orderBy('firstname', $direction)->orderBy('lastname', $direction);
                } else {
                    $query = $query->orderBy($sortBy, $direction);
                }
            } else {
                $query = $query->orderBy('role', 'ASC');
            }

            $users = $query->paginate(10);
            if(Request::has('route')) {
                $users->setBaseUrl('/admin/users/' . Request::get('route'));
            } else {
                $users->setBaseUrl('/admin/users');
            }

            $view = Request::has('view') ? Request::get('view') : 'list';
            $route = Request::has('route') ? Request::get('route') : 'index';

            $url = URL::route('admin.users.' . $route, $data);
            return Response::json([
                'success' => true,
                'url' => $url,
                'usersListHtmL' => (string) View::make('admin::users.' . $view, compact('users', 'url'))->render(),
                'usersPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $users)->render(),
                'usersCountHtmL' => (string) View::make('admin::parts.count')->with('models', $users)->render(),
            ]);
        }
    }

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		User::destroy($id);

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.users.index');
        return Redirect::to($backUrl);
	}

	public function changeRole($id)
	{
		if(Request::ajax()) {
			$role = Input::get('role');

			$user = User::find($id);
			$user->role = $role;
			if($user->save()) {
				$user->setNotification(Notification::TYPE_ROLE_CHANGED, [
					'[role]' => mb_strtolower(User::$roles[$role]),
				]);
				return Response::json(array(
					'success' => true,
					'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Права пользователя изменены.'])
				));
			}
		}
	}

	/**
	 * Display a listing of banned users
	 *
	 * @return Response
	 */
	public function banned()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
        $searchQuery = Request::get('query');

        $query = new User();
        $query = $query->with('banNotifications', 'latestBanNotification');
        $query = $query->whereIsBanned(1);
        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($q) use($searchQuery) {
                $q->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$searchQuery%")
                    ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$searchQuery%")
                    ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$searchQuery%")
                    ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$searchQuery%")
                    ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$searchQuery%")
                    ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$searchQuery%")
                    ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$searchQuery%")
                    ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$searchQuery%");
            });
        }

        if ($sortBy && $direction) {
            if ($sortBy == 'fullname') {
                $query = $query->orderBy('firstname', $direction)->orderBy('lastname', $direction);
            } else {
                $query = $query->orderBy($sortBy, $direction);
            }
        } else {
            $query = $query->orderBy('role', 'ASC');
        }

        $users = $query->paginate(10);

		return View::make('admin::users.bannedUsers', compact('users'));
	}

	/**
	 * Забанить пользователя
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function ban($id)
	{
		if(Request::ajax()) {
			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);
			if(!$formFields['message']) {
				$formFields['message'] = 'Сообщение о бане по умолчанию';
			}
			$user = User::find($id);
			if(!$user->isAdmin()) {
				$user->is_banned = 1;
				$user->setBanNotification($formFields['message']);
				$user->setNotification(Notification::TYPE_BANNED, ['[banMessage]' => $formFields['message']]);
				if($user->save()) {
					return Response::json([
						'success' => true,
						'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Пользователь забанен.']),
						'bannedImage' => (string) View::make('cabinet::user.bannedImage', ['user' => $user]),
					]);
				}
			} else {
				return Response::json([
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', ['siteMessage' => 'Администратора нельзя забанить.'])
				]);
			}
		}
	}

	/**
	 * Разбанить пользователя
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function unban($id)
	{
		if(Request::ajax()) {
			$user = User::find($id);
			if(!$user->isAdmin()) {
				$user->is_banned = 0;
				if($user->save()) {
					$banNotification = $user->banNotifications()->first();
					$banNotification->unban_at = date('Y:m:d H:i:s');
					$banNotification->save();
					$user->setNotification(Notification::TYPE_UNBANNED);
					return Response::json(array(
						'success' => true,
						'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Пользователь разбанен.'])
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Администратора нельзя разбанить, так как нельзя и забанить.'])
				));
			}
		}
	}

}

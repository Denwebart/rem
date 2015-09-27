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
            $query = $query->orderBy('role', 'ASC');
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
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $users = $query->paginate(10);

            $view = Request::has('view') ? Request::get('view') : 'list';
            $route = Request::has('route') ? Request::get('route') : 'index';
            return Response::json([
                'success' => true,
                'url' => URL::route('admin.users.' . $route, $data),
                'usersListHtmL' => (string) View::make('admin::users.' . $view, compact('users'))->render(),
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

		return Redirect::route('admin.users.index');
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
				));
			}
		}
	}

	/**
	 * Display a listing of banned users
	 *
	 * @return Response
	 */
	public function bannedUsers()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
        $searchQuery = Request::get('query');

        $query = new User();
        $query = $query->with('banNotifications', 'latestBanNotification');
        $query = $query->whereIsBanned(1);
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

	/**
	 * Display a listing of all ips
	 *
	 * @return Response
	 */
	public function ips()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$ips = Ip::orderBy($sortBy, $direction)->paginate(10);
		} else {
			$ips = Ip::orderBy('ban_at', 'DESC')->paginate(10);
		}

		return View::make('admin::users.ips', compact('ips'));
	}

	/**
	 * Display a listing of banned ips
	 *
	 * @return Response
	 */
	public function bannedIps()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$bannedIps = Ip::orderBy($sortBy, $direction)
				->whereIsBanned(1)
				->paginate(10);
		} else {
			$bannedIps = Ip::orderBy('ban_at', 'DESC')
				->whereIsBanned(1)
				->paginate(10);
		}

		return View::make('admin::users.bannedIps', compact('bannedIps'));
	}

	/**
	 * Забанить ip-адрес
	 *
	 * @param null $ipId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function banIp($ipId = null)
	{
		if(Request::ajax()) {
			if(is_null($ipId)) {
				$inputData = Input::get('formData');
				parse_str($inputData, $formFields);
				$ip = Ip::whereIp($formFields['ip'])->first();
				if(is_null($ip)) {
					$validator = Validator::make($formFields, Ip::$rules);
					if ($validator->fails()) {
						return Response::json(array(
							'fail' => true,
							'errors' => $validator->getMessageBag()->toArray(),
						));
					} else {
						$ip = new Ip();
						$ip->ip = $formFields['ip'];
						$ip->is_banned = 0;
					}
				}
			} else {
				$ip = Ip::find($ipId);
			}

			if($ip->is_banned) {
				return Response::json(array(
					'success' => false,
					'message' => 'IP-адрес уже забанен.',
				));
			}

			$ip->is_banned = 1;
			$ip->ban_at = date('Y:m:d H:i:s');

			if ($ip->save()) {
				$ipRowView = 'admin::users.bannedIpRow';
				return Response::json(array(
					'success' => true,
					'message' => 'Ip-адрес забанен.',
					'ipRowHtml' => is_null($ipId) ? (string) View::make($ipRowView, compact('ip'))->render() : '',
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Ошибка. Ip-адрес не был забанен.',
				));
			}
		}
	}

	/**
	 * Разбанить ip-адрес
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function unbanIp($id)
	{
		if(Request::ajax()) {
			$ip = Ip::find($id);
			$ip->is_banned = 0;
			$ip->unban_at = date('Y:m:d H:i:s');
			if($ip->save()) {
				return Response::json(array(
					'success' => true,
					'message' => 'Ip-адрес разбанен.'
				));
			}

		}
	}

	/**
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function ipsAutocomplete() {
		$term = Input::get('term');

		$result = Ip::whereIsBanned(0)
			->where('ip', 'like', "$term%")
			->lists('ip', 'id');

		return Response::json($result);
	}

}

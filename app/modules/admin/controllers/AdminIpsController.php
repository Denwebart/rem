<?php


class AdminIpsController extends \BaseController
{
    public function __construct(){
        if(Auth::check()){
            $headerWidget = app('HeaderWidget');
            View::share('headerWidget', $headerWidget);
        }
    }

    /**
     * Display a listing of all ips
     *
     * @return Response
     */
    public function index()
    {
        $sortBy = Request::get('sortBy');
        $direction = Request::get('direction');
        $searchQuery = Request::get('query');

        $query = new Ip();
        $query = $query->with('users', 'comments', 'letters');

        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($qu) use ($searchQuery) {
                $qu->whereHas('users', function($q) use ($searchQuery) {
                    $q->where(function($que) use ($searchQuery) {
                        $que->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$searchQuery%");
                    });
                })
                ->orWhere(DB::raw('LOWER(ip)'), 'LIKE', "$searchQuery%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('id', 'DESC');
        }

        $ips = $query->paginate(10);

        return View::make('admin::ips.index', compact('ips'));
    }

    /**
     * Поиск ip
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

            $query = new Ip();
            if(Request::get('route') == 'bannedIps') {
                $query = $query->whereIsBanned(1);
            }
            $query = $query->with('users', 'comments', 'letters');

            if ($searchQuery) {
                $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(function($qu) use ($searchQuery) {
                    $qu->whereHas('users', function($q) use ($searchQuery) {
                        $q->where(function($que) use ($searchQuery) {
                            $que->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$searchQuery%")
                                ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$searchQuery%")
                                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$searchQuery%")
                                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$searchQuery%")
                                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$searchQuery%")
                                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$searchQuery%")
                                ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$searchQuery%")
                                ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$searchQuery%");
                        });
                    })
                        ->orWhere(DB::raw('LOWER(ip)'), 'LIKE', "$searchQuery%");
                });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('id', 'DESC');
            }

            $ips = $query->paginate(10);
            if(Request::has('route')) {
                $ips->setBaseUrl('/admin/ips/' . Request::get('route'));
            } else {
                $ips->setBaseUrl('/admin/ips');
            }

            $view = Request::has('view') ? Request::get('view') : 'list';
            $route = Request::has('route') ? Request::get('route') : 'index';
            return Response::json([
                'success' => true,
                'url' => URL::route('admin.ips.' . $route, $data),
                'ipsListHtmL' => (string) View::make('admin::ips.' . $view, compact('ips'))->render(),
                'ipsPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $ips)->render(),
                'ipsCountHtmL' => (string) View::make('admin::parts.count')->with('models', $ips)->render(),
            ]);
        }
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
        $searchQuery = Request::get('query');

        $query = new Ip();
        $query = $query->whereIsBanned(1);
        $query = $query->with('users', 'comments', 'letters');
        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(function($qu) use ($searchQuery) {
                $qu->whereHas('users', function($q) use ($searchQuery) {
                    $q->where(function($que) use ($searchQuery) {
                        $que->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$searchQuery%")
                            ->orWhere(DB::raw('LOWER(email)'), 'LIKE', "$searchQuery%");
                    });
                })
                    ->orWhere(DB::raw('LOWER(ip)'), 'LIKE', "$searchQuery%");
            });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('id', 'DESC');
        }

        $ips = $query->paginate(10);

        return View::make('admin::ips.bannedIps', compact('ips'));
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
                    'message' => (string) View::make('widgets.siteMessages.warning', ['siteMessage' => 'IP-адрес уже забанен.']),
                ));
            }

            if(Request::ip() == $ip->ip) {
                return Response::json(array(
                    'success' => false,
                    'message' => (string) View::make('widgets.siteMessages.warning', ['siteMessage' => 'Ваш текущий ip-адрес нельзя забанить.']),
                ));
            }

            $ip->is_banned = 1;
            $ip->ban_at = date('Y:m:d H:i:s');

            if ($ip->save()) {
                $ipRowView = 'admin::ips.bannedIpRow';
                return Response::json(array(
                    'success' => true,
                    'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Ip-адрес забанен.']),
                    'ipRowHtml' => is_null($ipId) ? (string) View::make($ipRowView, compact('ip'))->render() : '',
                ));
            } else {
                return Response::json(array(
                    'success' => false,
                    'message' => (string) View::make('widgets.siteMessages.danger', ['siteMessage' => 'Ошибка. Ip-адрес не был забанен.']),
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
                    'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Ip-адрес разбанен.']),
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
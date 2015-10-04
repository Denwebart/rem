<?php

class AdminCommentsController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}

		$this->beforeFilter(function()
		{
			if(Auth::user()->isModerator()) {
				return Response::view('admin::errors.403', [], 403);
			}
		}, ['only' => ['destroy']]);
	}

	/**
	 * Display a listing of comments
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
        $author = Request::get('author');
        $status = Request::get('status');
        $searchQuery = Request::get('searchQuery');

        $query = new Comment;
        $query = $query->with('page.parent.parent', 'user');

		if ($author) {
			$name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ.@_ \-\']+$/u%', '', $author))));
			$query = $query->whereHas('user', function($q) use ($name) {
				$q->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "%$name%")
					->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "%$name%")
					->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "%$name%")
					->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "%$name%")
					->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "%$name%")
					->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "%$name%")
					->orWhere(DB::raw('LOWER(login)'), 'LIKE', "%$name%");
			})
			->orWhere(DB::raw('LOWER(user_name)'), 'LIKE', "%$name%")
			->orWhere(DB::raw('LOWER(user_email)'), 'LIKE', "%$name%");
		}
        if (!is_null($status) && $status !== '') {
            if(Comment::STATUS_DELETED == $status) {
                $query = $query->whereIsDeleted(1);
            } else {
                $query = $query->whereIsPublished($status);
                $query = $query->whereIsDeleted(0);
            }
        }
        if ($searchQuery) {
            $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(DB::raw('LOWER(comment)'), 'LIKE', "%$searchQuery%")
                ->orWhere(DB::raw('LOWER(ip)'), 'LIKE', "%$searchQuery%")
                ->orWhereHas('page', function($q) use ($searchQuery) {
                    $q->where(DB::raw('LOWER(meta_title)'), 'LIKE', "%$searchQuery%");
                });
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('created_at', 'DESC');
        }

        $comments = $query->paginate(10);

		return View::make('admin::comments.index', compact('comments'));
	}

    /**
     * Поиск комментариев
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
            $status = $data['status'];
            $searchQuery = $data['query'];

            $query = new Comment;
            $query = $query->with('page.parent.parent', 'user');

            if ($author) {
                $name = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ.@_ \-\']+$/u%', '', $author))));
                $query = $query->where(function($qu) use ($name) {
                    $qu->has('user');
	                $qu->whereHas('user', function($q) use ($name) {
		                $q->where(DB::raw('LOWER(CONCAT(login, " ", firstname, " ", lastname))'), 'LIKE', "$name%")
			                ->orWhere(DB::raw('LOWER(CONCAT(login, " ", lastname, " ", firstname))'), 'LIKE', "$name%")
			                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", firstname, " ", login))'), 'LIKE', "$name%")
			                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", lastname, " ", login))'), 'LIKE', "$name%")
			                ->orWhere(DB::raw('LOWER(CONCAT(firstname, " ", login, " ", lastname))'), 'LIKE', "$name%")
			                ->orWhere(DB::raw('LOWER(CONCAT(lastname, " ", login, " ", firstname))'), 'LIKE', "$name%")
			                ->orWhere(DB::raw('LOWER(login)'), 'LIKE', "$name%");
	                });
                })
                ->orWhere(function($q) use ($name) {
	                $q->whereNull('user_id');
	                $q->where(DB::raw('LOWER(user_name)'), 'LIKE', "$name%")
		                ->orWhere(DB::raw('LOWER(user_email)'), 'LIKE', "$name%");
                });
            }
	        /*
	        select * from `comments` where
	        (
			    (
			        select count(*) from `users`
			        where `comments`.`user_id` = `users`.`id`
			        and LOWER(CONCAT(login, " ", firstname, " ", lastname)) LIKE ?
			        or LOWER(CONCAT(login, " ", lastname, " ", firstname)) LIKE ?
			        or LOWER(CONCAT(lastname, " ", firstname, " ", login)) LIKE ?
			        or LOWER(CONCAT(firstname, " ", lastname, " ", login)) LIKE ?
			        or LOWER(CONCAT(firstname, " ", login, " ", lastname)) LIKE ?
			        or LOWER(CONCAT(lastname, " ", login, " ", firstname)) LIKE ?
			        or LOWER(login) LIKE ?
			    ) >= 1
			)
			or LOWER(user_name) LIKE 'test'
			or LOWER(user_email) LIKE 'test'

	        */
            if (!is_null($status) && $status !== '') {
                if(Comment::STATUS_DELETED == $status) {
                    $query = $query->whereIsDeleted(1);
                } else {
                    $query = $query->whereIsPublished($status);
                    $query = $query->whereIsDeleted(0);
                }
            }
            if ($searchQuery) {
                $searchQuery = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(DB::raw('LOWER(comment)'), 'LIKE', "%$searchQuery%")
                    ->orWhereHas('ip', function($q) use ($searchQuery) {
                        $q->where(DB::raw('LOWER(ip)'), 'LIKE', "%$searchQuery%");
                    })
                    ->orWhereHas('page', function($q) use ($searchQuery) {
                        $q->where(DB::raw('LOWER(title)'), 'LIKE', "%$searchQuery%")
                            ->orWhere(DB::raw('LOWER(menu_title)'), 'LIKE', "%$searchQuery%");
                    });
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('created_at', 'DESC');
            }

            $comments = $query->paginate(10);

            return Response::json([
                'success' => true,
                'url' => URL::route('admin.comments.index', $data),
                'commentsListHtmL' => (string) View::make('admin::comments.list', compact('comments'))->render(),
                'commentsPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $comments)->render(),
                'commentsCountHtmL' => (string) View::make('admin::parts.count')->with('models', $comments)->render(),
            ]);
        }
    }

	/**
	 * Display the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$comment = Comment::findOrFail($id);

		return View::make('admin::comments.show', compact('comment'));
	}

	/**
	 * Show the form for editing the specified comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$comment = Comment::find($id);

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('admin.comments.index');

		return View::make('admin::comments.edit', compact('comment', 'backUrl'));
	}

	/**
	 * Update the specified comment in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$comment = Comment::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Comment::$rulesForUpdate);
		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$comment->fill($data);
        $comment->comment = $comment->saveEditorImages($data['tempPath']);
        $comment->save();

		$backUrl = Input::has('backUrl')
			? Input::get('backUrl')
			: URL::route('admin.comments.index');

		return Redirect::to($backUrl);
	}

	/**
	 * Remove the specified comment from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$comment = Comment::find($id);
		$comment->markAsDeleted();

		return Redirect::route('admin.comments.index');
	}

	/**
	 * Remove the specified comment from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function ajaxMarkAsDeleted($id)
	{
		$comment = Comment::find($id);
		$parentId = $comment->parent_id;
		$comment->markAsDeleted();

		return Response::json(array(
			'success' => true,
			'parentId' => $parentId,
			'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Комментарий удален.']),
		));
	}

}

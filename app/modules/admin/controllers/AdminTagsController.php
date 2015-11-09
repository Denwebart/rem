<?php

class AdminTagsController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of tags
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
        $searchQuery = Request::get('query');

        $query = new Tag;
        $query = $query->with('pages');
        if ($searchQuery) {
            $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
            $query = $query->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                ->orWhere(DB::raw('LOWER(title)'), 'LIKE', "%". TranslitHelper::make($title) ."%");
        }

        if ($sortBy && $direction) {
            $query = $query->orderBy($sortBy, $direction);
        } else {
            $query = $query->orderBy('id', 'DESC');
        }
        $tags = $query->paginate(10);

		$tag = new Tag();

		return View::make('admin::tags.index', compact('tags', 'tag'));
	}

    /**
     * Поиск тегов
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

            $query = new Tag;
            $query = $query->with('pages');
            if ($searchQuery) {
                $title = mb_strtolower(trim(preg_replace('/ {2,}/', ' ', preg_replace('%/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u%', '', $searchQuery))));
                $query = $query->where(DB::raw('LOWER(title)'), 'LIKE', "%$title%")
                        ->orWhere(DB::raw('LOWER(title)'), 'LIKE', "%". TranslitHelper::make($title) ."%");
            }

            if ($sortBy && $direction) {
                $query = $query->orderBy($sortBy, $direction);
            } else {
                $query = $query->orderBy('id', 'DESC');
            }

            $tags = $query->paginate(10);

            $url = URL::route('admin.tags.index', $data);
            Session::set('user.url', $url);

            return Response::json([
                'success' => true,
                'url' => $url,
                'tagsListHtmL' => (string) View::make('admin::tags.list', compact('tags', 'url'))->render(),
                'tagsPaginationHtmL' => (string) View::make('admin::parts.pagination', compact('data'))->with('models', $tags)->render(),
                'tagsCountHtmL' => (string) View::make('admin::parts.count')->with('models', $tags)->render(),
                'resultHtml' => (string) View::make('admin::tags.search', compact('tags'))->render(),
            ]);
        }
    }

	/**
	 * Show the form for creating a new tag
	 *
	 * @return Response
	 */
	public function create()
	{
		$tag = new Tag();

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.tags.index');

		return View::make('admin::tags.create', compact('tag', 'backUrl'));
	}

	/**
	 * Store a newly created tag in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();

		$validator = Validator::make($data, Tag::rules(), Tag::$messages);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$tag = Tag::create($data);

		// загрузка изображения
		$tag->image = $tag->setImage($data['image']);
		$tag->save();

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.tags.index');
        return Redirect::to($backUrl);
	}

	/**
	 * Display the specified tag.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$tag = Tag::findOrFail($id);

		return View::make('admin::tags.show', compact('tag'));
	}

	/**
	 * Show the form for editing the specified tag.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$tag = Tag::find($id);

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.tags.index');

		return View::make('admin::tags.edit', compact('tag', 'backUrl'));
	}

	/**
	 * Update the specified tag in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$tag = Tag::findOrFail($id);

		$data = Input::all();

		$validator = Validator::make($data, Tag::rules($tag->id), Tag::$messages);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		$data['image'] = $tag->setImage($data['image']);

		$tag->update($data);

        $backUrl = Input::has('backUrl') ? Input::get('backUrl') : URL::route('admin.tags.index');
        return Redirect::to($backUrl);
	}

	/**
	 * Remove the specified tag from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Tag::destroy($id);

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('admin.tags.index');
        return Redirect::to($backUrl);
	}

	/**
	 * Удаление изображения
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteImage($id) {
		if(Request::ajax())
		{
			$tag = Tag::findOrFail($id);
			$imagePath = public_path() . '/uploads/' . $tag->getTable() . '/' . $tag->image;

			// delete old image
			if(File::exists($imagePath)) {
				File::delete($imagePath);
			}

			$tag->image = null;
			$tag->save();

			return Response::json([
				'success' => true,
			]);
		}
	}

	/**
	 * Merge tags
	 *
	 * @return Response
	 */
	public function merge()
	{
		return View::make('admin::tags.merge');
	}

	public function postMerge()
	{
		if(Request::ajax()) {

			$formData = Input::get('formData');
			parse_str($formData, $formFields);

			$tags = Tag::whereIn('title', $formFields['tags'])->with('pagesTags')->get();
			$resultTag = Tag::whereTitle($formFields['resultTag'])->first();

			if(is_null($resultTag)) {
				return Response::json(array(
					'success' => false,
					'message' => 'Такого тега не существует',
				));
			}

			foreach($tags as $tag) {
				foreach($tag->pagesTags as $item) {
					if(!PageTag::whereTagId($resultTag->id)->wherePageId($item->page_id)->first()) {
						PageTag::create(['page_id' => $item->page_id, 'tag_id' => $resultTag->id]);
					}
				}
				if($tag->id != $resultTag->id) {
					$tag->delete();
				}
			}

			return Response::json(array(
				'success' => true,
				'message' => 'Объединено в тег "' . $resultTag->title . '".',
			));
		}
	}

	public function autocomplete() {
		$term = Input::get('term');
		$result = Tag::where('title', 'like', "$term%")
			->lists('title', 'id');

		return Response::json($result);
	}

}

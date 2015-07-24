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
		if ($sortBy && $direction) {
			$tags = Tag::orderBy($sortBy, $direction)->with('pages')->paginate(10);
		} else {
			$tags = Tag::orderBy('id', 'DESC')->with('pages')->paginate(10);
		}

		$tag = new Tag();

		return View::make('admin::tags.index', compact('tags', 'tag'));
	}

	/**
	 * Show the form for creating a new tag
	 *
	 * @return Response
	 */
	public function create()
	{
		$tag = new Tag();
		return View::make('admin::tags.create', compact('tag'));
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
		$tag->setImage($data['image']);

		return Redirect::route('admin.tags.index');
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

		return View::make('admin::tags.edit', compact('tag'));
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

		$tag->update($data);

		// загрузка изображения
		$tag->setImage($data['image']);

		return Redirect::route('admin.tags.index');
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

		return Redirect::route('admin.tags.index');
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

	public function search() {

		if(Request::ajax()) {

			$formData = Input::get('formData');
			parse_str($formData, $formFields);
			$search = $formFields['search'];

			$tags = Tag::where('title', 'LIKE', "%$search%")
				->orWhere('title', 'LIKE', "%" . TranslitHelper::make($search) . "%")
				->with('pagesTags')
				->get();

			return Response::json(array(
				'success' => true,
				'resultHtml' => (string) View::make('admin::tags.search', compact('tags'))->render(),
			));
		}

	}

}

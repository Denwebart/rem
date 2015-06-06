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
			$tags = Tag::orderBy('title', 'ASC')->with('pages')->paginate(10);
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
		$tag->user_id = Auth::user()->id;

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

		$validator = Validator::make($data, Tag::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['image'])){

			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . (new Tag)->getTable() . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();
			$image->crop($cropSize, $cropSize)
				->resize(300, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . $fileName);

			$data['image'] = $fileName;
		}
		// загрузка изображения

		Tag::create($data);

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

		$validator = Validator::make($data, Tag::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['image'])){

			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $tag->getTable() . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();
			$image->crop($cropSize, $cropSize)
				->resize(300, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . $fileName);

			// delete old image
			if(File::exists($imagePath . $tag->image)) {
				File::delete($imagePath . $tag->image);
			}

			$data['image'] = $fileName;
		} else {
			$data['image'] = $tag->image;
		}
		// загрузка изображения

		$tag->update($data);

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

}

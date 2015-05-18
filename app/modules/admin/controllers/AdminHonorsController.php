<?php

class AdminHonorsController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of honors
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$honors = Honor::orderBy($sortBy, $direction)->paginate(10);
		} else {
			$honors = Honor::orderBy('id', 'DESC')->paginate(10);
		}

		return View::make('admin::honors.index', compact('honors'));
	}

	/**
	 * Show the form for creating a new page
	 *
	 * @return Response
	 */
	public function create()
	{
		$honor = new Honor();

		return View::make('admin::honors.create', compact('honor'));
	}

	/**
	 * Store a newly created page in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$validator = Validator::make($data, Honor::$rules);

		if($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['image'])){
			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . (new Honor)->getTable() . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			$image->save($imagePath . $fileName);

			$data['image'] = $fileName;
		}
		// загрузка изображения

		Honor::create($data);

		return Redirect::route('admin.honors.index');
	}

	/**
	 * Show the form for editing the specified honor.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$honor = Honor::find($id);

		return View::make('admin::honors.edit', compact('honor'));
	}

	/**
	 * Update the specified honor in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$honor = Honor::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Honor::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['image'])){
			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . (new Honor)->getTable() . '/';
			$image = Image::make($data['image']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			$image->save($imagePath . $fileName);

			$data['image'] = $fileName;
		}
		// загрузка изображения

		$honor->update($data);

		return Redirect::route('admin.honors.index');
	}

	/**
	 * Remove the specified honor from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Honor::destroy($id);

		return Redirect::route('admin.honors.index');
	}

}

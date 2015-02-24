<?php

class AdminLettersController extends \BaseController {

	public function __construct(){
		$headerWidget = app('HeaderWidget');
		View::share('headerWidget', $headerWidget);
	}

	/**
	 * Display a listing of letters
	 *
	 * @return Response
	 */
	public function index()
	{
		$letters = Letter::orderBy('created_at', 'DESC')->whereNull('deleted_at')->paginate(10);

		return View::make('admin::letters.index', compact('letters'));
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

		return Redirect::route('admin.letters.index');
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

}

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
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$letters = Letter::orderBy($sortBy, $direction)->whereNull('deleted_at')->paginate(10);
		} else {
			$letters = Letter::orderBy('created_at', 'DESC')->whereNull('deleted_at')->paginate(10);
		}
		return View::make('admin::letters.index', compact('letters'));
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
		if ($sortBy && $direction) {
			$letters = Letter::orderBy($sortBy, $direction)->whereNotNull('deleted_at')->paginate(10);
		} else {
			$letters = Letter::orderBy('deleted_at', 'DESC')->whereNotNull('deleted_at')->paginate(10);
		}

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

<?php

class AdminLettersController extends \BaseController {

	/**
	 * Display a listing of letters
	 *
	 * @return Response
	 */
	public function index()
	{
		$letters = Letter::orderBy('created_at', 'DESC')->paginate(10);

		return View::make('admin.letters.index', compact('letters'));
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

		return View::make('admin.letters.show', compact('letter'));
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

}

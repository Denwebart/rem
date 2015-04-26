<?php

class AdminMessagesController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of messages
	 *
	 * @return Response
	 */
	public function index()
	{
		$messages = Message::all();

		return View::make('messages.index', compact('messages'));
	}

	/**
	 * Show the form for creating a new message
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('messages.create');
	}

	/**
	 * Store a newly created message in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Message::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Message::create($data);

		return Redirect::route('messages.index');
	}

	/**
	 * Display the specified message.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$message = Message::findOrFail($id);

		return View::make('messages.show', compact('message'));
	}

	/**
	 * Show the form for editing the specified message.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$message = Message::find($id);

		return View::make('messages.edit', compact('message'));
	}

	/**
	 * Update the specified message in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$message = Message::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Message::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$message->update($data);

		return Redirect::route('messages.index');
	}

	/**
	 * Remove the specified message from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Message::destroy($id);

		return Redirect::route('messages.index');
	}

}

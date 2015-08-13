<?php

class AdminNotificationsMessagesController extends \BaseController {

	public function __construct(){
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}
	}

	/**
	 * Display a listing of notifications messages.
	 *
	 * @return Response
	 */
	public function index()
	{
		$sortBy = Request::get('sortBy');
		$direction = Request::get('direction');
		if ($sortBy && $direction) {
			$notificationsMessages = NotificationMessage::orderBy($sortBy, $direction)->paginate(10);
		} else {
			$notificationsMessages = NotificationMessage::orderBy('id', 'ASC')->paginate(10);
		}

		return View::make('admin::notificationsMessages.index', compact('notificationsMessages'));
	}

	/**
	 * Show the form for editing the specified notification message.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$notificationMessage = NotificationMessage::find($id);

		return View::make('admin::notificationsMessages.edit', compact('notificationMessage'));
	}

	/**
	 * Update the specified notification message in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$notificationMessage = NotificationMessage::findOrFail($id);

		$validator = Validator::make($data = Input::all(), NotificationMessage::rules($notificationMessage->id));

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$notificationMessage->update($data);

		return Redirect::route('admin.notificationsMessages.index');
	}
}
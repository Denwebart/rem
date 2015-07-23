<?php

class BaseController extends Controller {

	public function __construct()
	{
		View::share('settings', Setting::getSettings());
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function getMessage($message, $status, $redirect = false)
	{
		return View::make('message', array(
			'message'   => $message,
			'status'   => $status,
			'redirect'  => $redirect,
		));
	}
}

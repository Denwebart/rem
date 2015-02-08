<?php

class AdminController extends Controller
{
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function getMessage($message, $redirect = false)
	{
		return View::make('message', array(
			'message'   => $message,
			'redirect'  => $redirect,
		));
	}
}
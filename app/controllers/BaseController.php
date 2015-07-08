<?php

class BaseController extends Controller {

	public function __construct()
	{
		// Perform CSRF check on all post/put/patch/delete requests
		$this->beforeFilter('csrf', ['on' => ['post', 'put', 'patch', 'delete']]);
		$this->beforeFilter('ajax', array('on' => array('delete', 'put', 'post')));
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

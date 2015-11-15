<?php

class RemindersController extends Controller {

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{
		$validator = Validator::make(Input::only('email'), ['email' => 'required|email']);

		if($validator->passes()) {
			$siteEmail = ($siteEmailModel = Setting::whereKey('siteEmail')->whereIsActive(1)->first())
				? $siteEmailModel->value
				: Config::get('settings.adminEmail');
			$template = EmailTemplate::whereKey('changePassword')->first();
			switch ($response = Password::remind(Input::only('email'), function($message) use ($siteEmail, $template) {
				$variables = [
					'[siteUrl]' => Config::get('settings.siteUrl'),
					'[resetUrl]' => URL::to('password/reset', array($message->token)),
					'[expireTime]' => Config::get('auth.reminder.expire', 60),
				];
				$content = strtr($template->html, $variables);

				$message->with(['content' => $content]);
				$message->from($siteEmail, Config::get('settings.adminName'));
				$message->subject($template->subject);
			}))
			{
				case Password::INVALID_USER:
					return Redirect::back()->with('error', Lang::get($response));

				case Password::REMINDER_SENT:
					return Redirect::back()->with('status', Lang::get($response));
			}
		} else {
			return Redirect::back()->withErrors($validator)->withInput();
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);
			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::to('/');
		}
	}

}

<?php

class UsersController extends BaseController
{
	public function getRegister() {
		return View::make('users.register');
	}

	public function postRegister()
	{
		// Проверка входных данных
		$rules = User::$rules['registration'];
		$validation = Validator::make(Input::all(), $rules);
		if ($validation->fails()) {
			// В случае провала, редиректим обратно с ошибками и самими введенными данными
			return Redirect::route('register')->withErrors($validation)->withInput();
		}

		// Сама регистрация с уже проверенными данными=
		$user = new User();
		$user->fill(Input::all());
		$id = $user->register();

		// Вывод информационного сообщения об успешности регистрации
		return $this->getMessage("Регистрация почти завершена. Вам необходимо подтвердить e-mail, указанный при регистрации, перейдя по ссылке в письме.", 'info');
	}

	public function getActivate($userId, $activationCode)
	{
		// Получаем указанного пользователя
		$user = User::find($userId);
		if (!$user) {
			return $this->getMessage("Неверная ссылка на активацию аккаунта.", 'danger');
		}

		// Пытаемся его активировать с указанным кодом
		if ($user->activate($activationCode)) {
			// В случае успеха авторизовываем его
			Auth::login($user);
			// И выводим сообщение об успехе
			return $this->getMessage("Аккаунт активирован", 'success',  "/");
		}

		// В противном случае сообщаем об ошибке
		return $this->getMessage("Неверная ссылка на активацию аккаунта, либо учетная запись уже активирована.", 'danger');
	}

	public function getLogin() {
		Session::put('previousUrl', URL::previous());
		return View::make('users.login');
	}

	public function postLogin() {
		// Формируем базовый набор данных для авторизации
		// (isActive => 1 нужно для того, чтобы аторизоваться могли только
		// активированные пользователи)
		$creds = array(
			'password' => Input::get('password'),
			'is_active'  => 1,
		);

		// В зависимости от того, что пользователь указал в поле login,
		// дополняем авторизационные данные
		$login = Input::get('login');
		if (strpos($login, '@')) {
			$creds['email'] = $login;
		} else {
			$creds['login'] = $login;
		}

		// Пытаемся авторизовать пользователя
		if (Auth::attempt($creds, Input::has('remember'))) {
			Log::info("User [{$login}] successfully logged in.");

			// Вытираем предыдущую сессию
			Session::forget('user');
			// Редирект в админку (если админ) или на предыдущую (для остальных)
			if(Auth::user()->isAdmin()){
				return Redirect::to('admin');
			} else {
				if(Session::has('previousUrl')) {
					return Redirect::to(Session::get('previousUrl'));
				} else {
					return Redirect::to('/');
				}
			}
		} else {
			Log::info("User [{$login}] failed to login.");
		}

		$alert = "Неверная комбинация имени (email) и пароля, либо учетная запись еще не активирована.";

		// Возвращаем пользователя назад на форму входа с временной сессионной
		// переменной alert (withAlert)
		return Redirect::back()->withAlert($alert);
	}

	public function getLogout() {
		if(Auth::check()){
			Auth::logout();
		}
		Session::forget('user');
		if(preg_match('#^'.Config::get('app.url').'user#', URL::previous()) || preg_match('#^'.Config::get('app.url').'admin#', URL::previous()))
		{
			return Redirect::to('/');
		}
		else {
			return Redirect::to(URL::previous());
		}
	}
}
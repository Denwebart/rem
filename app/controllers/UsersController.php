<?php

class UsersController extends BaseController
{
	public function getRegister()
	{
		if(URL::previous() != URL::current()) {
			Session::put('user.previousUrl', URL::previous());
		}
		return View::make('users.register');
	}

	public function postRegister()
	{
		// Проверка входных данных
		$rules = User::$rules['registration'];
		$validation = Validator::make(
			Input::all() + ['alias' => TranslitHelper::make(Input::get('login'))],
			$rules,
			['alias.unique' => 'Пользователь с таким логином уже зарегистрирован.']
		);
		if ($validation->fails()) {
			// В случае провала, редиректим обратно с ошибками и самими введенными данными
			return Redirect::route('register')->withErrors($validation)->withInput();
		}

		// Сама регистрация с уже проверенными данными
		$user = new User();
		$user->fill(Input::all());
		$user->password = Input::get('password');
		$id = $user->register();

		// Вывод информационного сообщения об успешности регистрации
		return $this->getMessage("<b>Регистрация почти завершена!</b><br> Письмо с подтверждением отправлено на email, указанный при регистрации. Для активации аккаунта перейдите по ссылке, которая указана в письме.", 'info');
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

			/* уведомление админам о новом пользователе */
			$adminsModel = User::whereRole(User::ROLE_ADMIN)->whereIsActive(1)->whereIsBanned(0)->get();
			foreach ($adminsModel as $admin) {
				$admin->setNotification(Notification::TYPE_FOR_ADMIN_NEW_USER, [
					'[user]' => $user->login,
					'[linkToUser]' => URL::route('user.profile', ['login' => $user->getLoginForUrl()]),
				]);
			}

			// И выводим сообщение об успехе
			return $this->getMessage("Аккаунт активирован", 'success',  "/");
		}

		// В противном случае сообщаем об ошибке
		return $this->getMessage("Неверная ссылка на активацию аккаунта, либо учетная запись уже активирована.", 'danger');
	}

	public function getLogin()
	{
		if(URL::previous() != URL::current()) {
			if(URL::previous() != URL::route('register')) {
				Session::put('user.previousUrl', URL::previous());
			} else {
				Session::put('user.previousUrl', Session::has('user.previousUrl') ? Session::get('user.previousUrl') : '/');
			}
		}
		return View::make('users.login');
	}

	public function postLogin()
	{
		// Формируем базовый набор данных для авторизации
		// (isActive => 1 нужно для того, чтобы аторизоваться могли только
		// активированные пользователи)
		$creds = [
			'password' => Input::get('password'),
			'is_active'  => 1,
		];

		// В зависимости от того, что пользователь указал в поле login,
		// дополняем авторизационные данные
		$login = Input::get('login');
		if (strpos($login, '@')) {
			$creds['email'] = $login;
		} else {
			$creds['login'] = $login;
		}

		$validator = Validator::make($creds/* + ['sckey' => Input::get('sckey')]*/, User::$rules['login']);

		if ($validator->passes()) {
			// Пытаемся авторизовать пользователя
			if (Auth::attempt($creds, Input::has('remember'))) {
				Log::info("User [{$login}] successfully logged in.");

				Auth::user()->setIp(Request::ip());
				Auth::user()->setOnline(1);

				$urlPrevious = Session::has('user.previousUrl') ? Session::get('user.previousUrl') : false;

				// Вытираем предыдущую сессию
				Session::forget('user');
				// HeaderWidget cache
				if(Auth::user()->isAdmin() || Auth::user()->isModerator()) {
					Cache::forget('headerWidget.newQuestions');
					Cache::forget('headerWidget.newArticles');
					Cache::forget('headerWidget.newComments');
					Cache::forget('headerWidget.newAnswers');
					Cache::forget('headerWidget.notPublishedAnswers');
					Cache::forget('headerWidget.notPublishedComments');
				}
				if(Auth::user()->isAdmin()) {
					Cache::forget('headerWidget.newLetters');
					Cache::forget('headerWidget.deletedLetters');
					Cache::forget('headerWidget.newUsers');
				}
				Cache::forget('headerWidget.newMessages.' . Auth::user()->id);
				Cache::forget('headerWidget.newNotifications.' . Auth::user()->id);
				Cache::forget('headerWidget.newSubscriptionsNotifications.' . Auth::user()->id);
				// end HeaderWidget cache

				// Заносим в сессию время последнего визита
				Session::set('user.entryTime', \Carbon\Carbon::now());
				Session::set('user.lastActivity', Auth::user()->last_activity);

				// Редирект на предыдущую или на главную
				if($urlPrevious) {
					return Redirect::to($urlPrevious);
				} else {
					return Redirect::to('/');
				}
			} else {
				Log::info("User [{$login}] failed to login.");
			}

			$message = "Неверный логин (email) или пароль, либо учетная запись еще не активирована.";

			// Возвращаем пользователя назад на форму входа с временной сессионной
			// переменной alert (withAlert)
			return Redirect::back()->withAlert($message)->withInput();
		}
		else {
			return Redirect::back()->withErrors($validator)->withInput();
		}
	}

	public function getLogout()
	{
		if(Auth::check()){

			// Вытираем предыдущую сессию
			Session::forget('user');
			// HeaderWidget cache
			if(Auth::user()->isAdmin() || Auth::user()->isModerator()) {
				Cache::forget('headerWidget.newQuestions');
				Cache::forget('headerWidget.newArticles');
				Cache::forget('headerWidget.newComments');
				Cache::forget('headerWidget.newAnswers');
				Cache::forget('headerWidget.notPublishedAnswers');
				Cache::forget('headerWidget.notPublishedComments');
			}
			if(Auth::user()->isAdmin()) {
				Cache::forget('headerWidget.newLetters');
				Cache::forget('headerWidget.deletedLetters');
				Cache::forget('headerWidget.newUsers');
			}
			Cache::forget('headerWidget.newMessages.' . Auth::user()->id);
			Cache::forget('headerWidget.newNotifications.' . Auth::user()->id);
			Cache::forget('headerWidget.newSubscriptionsNotifications.' . Auth::user()->id);
			// end HeaderWidget cache

			Auth::user()->setOnline(0);
			Auth::logout();
		}

		if(strpos(URL::previous(), Config::get('settings.siteUrl').'/admin')) {
			return Redirect::to('/');
		} elseif(is_null(Request::get('backUrl'))) {
			return Redirect::to(URL::previous());
		} else {
			return Redirect::to(urldecode(Request::get('backUrl')));
		}
	}

	/**
	 * Страница с правилами сайта
	 *
	 * @return \Illuminate\View\View
	 */
	public function getRules($alias)
	{
		$rules = Rule::whereIsPublished(1)->orderBy('position', 'ASC')->get(['id', 'position', 'is_published', 'title', 'description']);

		$page = Page::getPageByAlias($alias)->firstOrFail();
		if(Auth::check()){
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);

			if(!Auth::user()->is_agree) {
				$backUrl = Request::get('backUrl')
					? urldecode(Request::get('backUrl'))
					: URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]);
				$user = Auth::user();
				return View::make('users.rulesForAuth', compact('user', 'rules', 'backUrl', 'page'));
			}
			else {
				return View::make('users.rulesForGuest', compact('page', 'rules'));
			}
		}
		else {
			return View::make('users.rulesForGuest', compact('page', 'rules', 'backUrl'));
		}
	}

	/**
	 * Соглашение с правилами сайта
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postRules()
	{
		$rulesFromInput = Input::get('rules');
		$rules = Rule::whereIsPublished(1)->orderBy('position', 'ASC')->get();

		if(count($rules) == count($rulesFromInput)) {
			$user = Auth::user();
			$user->is_agree = 1;
			if($user->save()) {
				return Redirect::to(Input::get('backUrl'))->with('rulesSuccessMessage', 'Спасибо, что согласились с правилами сайта.');
			}
		} else {
			return Redirect::route('rules')->with('rulesErrorMessage', 'Вы не подтвердили согласие со всеми правилами сайта.');
		}
	}
}
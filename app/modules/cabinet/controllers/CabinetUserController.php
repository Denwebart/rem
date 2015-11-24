<?php

class CabinetUserController extends \BaseController
{
	public function __construct()
	{
		parent::__construct();

		$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_CABINET]);
		View::share('areaWidget', $areaWidget);

		if(Auth::check()) {
			$headerWidget = app('HeaderWidget');
			View::share('headerWidget', $headerWidget);
		}

		// согласие с правилами сайта
		$this->beforeFilter(function()
		{
			if(Auth::check()) {
				$login = Route::current()->getParameter('login');
				$backUrl = Request::url();
				if(Auth::user()->getLoginForUrl() == $login) {
					if(!Auth::user()->is_agree) {
						return Redirect::route('rules', ['rulesAlias' => 'pravila-sajta', 'backUrl' => urlencode($backUrl)]);
					}
				}
			}

		}, ['except' => ['index', 'savedPages', 'savePage', 'removePage', 'removeAllPages', 'subscriptions', 'subscribe', 'unsubscribe', 'unsubscribeFromAll', 'deleteSubscriptionNotification', 'getChangePassword', 'postChangePassword', 'getSettings', 'postSettings', 'notifications', 'deleteNotification', 'deleteAllNotifications']]);

		// бан пользователя
		$this->beforeFilter(function() use ($headerWidget)
		{
			if(Auth::check()) {
				$login = Route::current()->getParameter('login');
				if(Auth::user()->getLoginForUrl() == $login) {
					if(Auth::user()->is_banned) {
						return View::make('cabinet::user.ban')->with('user', Auth::user());
					} elseif($headerWidget->isBannedIp) {
						return View::make('cabinet::user.banIp')->with('user', Auth::user());
					}
				}
			}
		}, ['except' => ['index', 'gallery', 'questions', 'journal', 'comments', 'answers', 'messages', 'dialog', 'markMessageAsRead', 'savedPages', 'savePage', 'removePage', 'removeAllPages', 'subscriptions', 'subscribe', 'unsubscribe', 'unsubscribeFromAll', 'deleteSubscriptionNotification', 'notifications', 'deleteNotification', 'deleteAllNotifications']]);

		$this->beforeFilter(function()
		{
			$login = Route::current()->getParameter('login');

			if(Auth::user()->getLoginForUrl() != $login && Auth::user()->isUser()) {
				App::abort(403, 'Unauthorized action.');
			}
			View::share('backUrlLogout', '/');

		}, ['only' => ['editPhoto', 'deletePhoto', 'deleteComment', 'deleteAnswer']]);

		$this->beforeFilter(function()
		{
			$login = Route::current()->getParameter('login');
			if(Auth::user()->getLoginForUrl() != $login && !Auth::user()->isAdmin()) {
				App::abort(403, 'Unauthorized action.');
			}
			View::share('backUrlLogout', '/');

		}, ['except' => ['index', 'gallery', 'editPhoto', 'deletePhoto', 'questions', 'journal', 'comments', 'deleteComment', 'deleteAnswer', 'answers', 'subscriptions']]);
	}

	public function index($login)
	{
		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail())
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		View::share('user', $user);
		return View::make('cabinet::user.index');
	}

	public function edit($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		View::share('user', $user);

        $backUrl = Request::has('backUrl')
            ? urldecode(Request::get('backUrl'))
            : URL::route('user.profile', ['login' => $user->getLoginForUrl()]);

		return View::make('cabinet::user.edit', compact('backUrl'));
	}

	/**
	 * Обновление профиля
	 *
	 * @param $login
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postEdit($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$data = Input::all();

		$data['avatar'] = isset($data['avatar'])
			? $data['avatar']
			: (isset($data['avatar_mobile'])
				? $data['avatar_mobile']
				: null);

		$data['firstname'] = StringHelper::mbUcFirst(Input::get('firstname'));
		$data['lastname'] = StringHelper::mbUcFirst(Input::get('lastname'));
		$data['car_brand'] = StringHelper::mbUcFirst(Input::get('car_brand'));
		$data['profession'] = StringHelper::mbUcFirst(Input::get('profession'));

		$validator = Validator::make($data, $user->getValidationRules());

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$data['description'] = StringHelper::nofollowLinks($data['description']);

		// загрузка изображения
		$data['avatar'] = $user->setAvatar($data['avatar']);

		if(isset($data['role'])) {
			if($user->role != $data['role']) {
				$user->setNotification(Notification::TYPE_ROLE_CHANGED, [
					'[role]' => mb_strtolower(User::$roles[$data['role']]),
				]);
			}
		}

		$user->fill($data);
        $user->description = $user->saveEditorImages($data['tempPath']);
        $user->save();

		// очистка кэша
		if(count($user->bestPublishedAnswers)) {
			Cache::forget('widgets.answers');
		}
		if(count($user->publishedComments)) {
			Cache::forget('widgets.comments');
		}
		if(count($user->publishedQuestions)) {
			Cache::forget('widgets.questions');
		}

        $backUrl = Input::has('backUrl')
            ? Input::get('backUrl')
            : URL::route('user.profile', ['login' => $user->getLoginForUrl()]);

        return Redirect::to($backUrl);
	}

	public function getChangePassword($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		View::share('user', $user);
		return View::make('cabinet::user.changePassword');
	}

	/**
	 * Смена пароля
	 *
	 * @param $login
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postChangePassword($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$data = Input::all();

		$validator = Validator::make($data, [
			'password' => 'required|min:6|max:100',
			'newpassword' => 'required|confirmed|min:6|max:100',
		]);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		if (Hash::check($data['password'], Auth::user()->password)) {
			$user->password = Hash::make($data['newpassword']);
			$user->save();

			return Redirect::route('user.profile', ['login' => $user->getLoginForUrl()])->with('successMessage', 'Пароль успешно обновлен.');

		} else {
			return Redirect::back()->withErrors(['password' => 'Введенный пароль не совпадает с текущим.']);
		}

	}

	/**
	 * Изменение настроек профиля
	 *
	 * @param $login
	 * @return \Illuminate\View\View
	 */
	public function getSettings($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$userSettings = UserSetting::whereUserId($user->id)->first();
		if(!is_object($userSettings)) {
			$userSettings = UserSetting::create([
				'user_id' => $user->id,
				'notification_deleted' => 1,
				'notification_points' => 1,
				'notification_new_comments' => 1,
				'notification_new_answers' => 1,
				'notification_like_dislike' => 1,
				'notification_best_answer' => 1,
				'notification_rating' => 1,
				'notification_journal_subscribed' => 1,
				'notification_question_subscribed' => 1,
				'notification_banned' => 1,
				'notification_role_changed' => 1,
			]);
		}

		View::share('user', $user);
		return View::make('cabinet::user.settings', compact('userSettings'));
	}

	/**
	 * Изменение настроек профиля
	 *
	 * @param $login
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postSettings($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$data = Input::all();

		$userSettings = UserSetting::whereUserId($user->id)->first();
		$userSettings->update($data);

		return Redirect::route('user.profile', ['login' => $user->getLoginForUrl()])->with('successMessage', 'Настройки изменены.');

	}

	/**
	 * Удаление изображения
	 *
	 * @param $login
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteAvatar($login) {
		if(Request::ajax())
		{
			$user = User::whereAlias($login)->firstOrFail();
			$imagePath = public_path() . '/uploads/' . $user->getTable() . '/' . $user->getLoginForUrl() . '/';

			// delete old avatar
			if(File::exists($imagePath . $user->avatar)) {
				File::delete($imagePath . $user->avatar);
			}
			if(File::exists($imagePath . 'origin_' . $user->avatar)){
				File::delete($imagePath . 'origin_' . $user->avatar);
			}
			if(File::exists($imagePath . 'mini_' . $user->avatar)){
				File::delete($imagePath . 'mini_' . $user->avatar);
			}

			$user->avatar = null;
			$user->save();

			return Response::json([
				'success' => true,
				'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Фотография удалена.']),
				'imageUrl' => Config::get('settings.defaultAvatar'),
				'imageUrlMini' => Config::get('settings.mini_defaultAvatar'),
			]);
		}
	}

	public function gallery($login)
	{
		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail())
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$images = $user->images()
			->select('id', 'user_id', 'is_published', 'title', 'image', 'description', 'created_at')
			->with([
				'user' => function($query) {
					$query->select('id', 'login', 'alias');
				}
			])
			->get();

		View::share('user', $user);
		return View::make('cabinet::user.gallery', compact('images'));
	}

	public function uploadPhoto($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->firstOrFail();
		$data = Input::all();
		$data['description'] = StringHelper::nofollowLinks($data['description']);
		$data['user_id'] = $user->id;
		$data['is_published'] = 1;
		$validator = Validator::make($data, UserImage::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$usersImage = UserImage::create($data);

		// загрузка изображения
		$usersImage->image = $usersImage->setImage($data['image'], $user);
		$usersImage->save();


		return Redirect::route('user.gallery', ['login' => $user->login]);

	}

	public function deletePhoto($login)
	{
		if(Request::ajax())
		{
			$user = (Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->firstOrFail();
			$image = UserImage::whereId(Input::get('imageId'))->whereUserId($user->id)->firstOrFail();
			$imagePath = public_path() . '/uploads/' . $image->getTable() . '/' . $login . '/';

			if(File::exists($imagePath . $image->image)) {
				File::delete($imagePath . $image->image);
			}

			$image->delete();

			return Response::json([
				'success' => true,
				'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Изображение удалено.'])
			]);
		}
	}

	public function editPhoto($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->firstOrFail();
		$usersImage = UserImage::whereId($id)->whereUserId($user->id)->firstOrFail();

		if($data = Input::all()) {

			$data['description'] = StringHelper::nofollowLinks($data['description']);
			$data['user_id'] = $user->id;
			$data['is_published'] = 1;
			$validator = Validator::make($data, UserImage::$rulesEdit);

			if ($validator->fails())
			{
				return Redirect::back()->withErrors($validator)->withInput();
			}

			// загрузка изображения
			$data['image'] = $usersImage->setImage($data['image'], $user);

			if($usersImage->update($data)) {
				return Redirect::route('user.gallery', ['login' => $user->login]);
			}
		}

		return View::make('cabinet::user.galleryEdit', compact('user'))->with('image', $usersImage);
	}

	public function questions($login)
	{
		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail())
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		if(Auth::check()){
			if(Auth::user()->getLoginForUrl() == $login || Auth::user()->isAdmin()) {
				$questions = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters'])
					->whereType(Page::TYPE_QUESTION)
					->whereUserId($user->id)
					->with('parent.parent', 'publishedComments', 'bestComments')
					->orderBy('created_at', 'DESC')
					->paginate(10);
			} else {
				$questions = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters'])
					->whereType(Page::TYPE_QUESTION)
					->whereUserId($user->id)
					->whereIsPublished(1)
					->with('parent.parent', 'publishedComments', 'bestComments')
					->orderBy('created_at', 'DESC')
					->paginate(10);
			}
		} else {
			$questions = Page::select(['id', 'alias', 'title', 'type', 'is_published', 'is_container', 'user_id', 'parent_id', 'published_at', 'views', 'votes', 'voters'])
				->whereType(Page::TYPE_QUESTION)
				->whereUserId($user->id)
				->whereIsPublished(1)
				->with('parent.parent', 'publishedComments', 'bestComments')
				->orderBy('created_at', 'DESC')
				->paginate(10);
		}
		View::share('user', $user);
		return View::make('cabinet::user.questions', compact('questions'));
	}

	public function createQuestion($login)
	{
		$question = new Page();
		if(Input::get('category')) {
			$question->parent_id = Input::get('category');
		}
		$question->type = Page::TYPE_QUESTION;
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('user.questions', ['login' => $login]);

		View::share('user', $user);
		return View::make('cabinet::user.createQuestion', compact('question', 'backUrl'));
	}

	public function storeQuestion($login)
	{
		$premoderation = Setting::whereKey('articlesPremoderation')->select('value')->first();
		$isPublished = $premoderation->value ? 0 : 1;

		$data = Input::all();

		$data['type'] = Page::TYPE_QUESTION;
		$data['user_id'] = Auth::user()->id;
		$data['content'] = StringHelper::nofollowLinks($data['content']);
		$data['is_published'] = $isPublished;
		$data['published_at'] = \Carbon\Carbon::now();
		$data['alias'] = TranslitHelper::make($data['title']);

		$validator = Validator::make($data, Page::rules('create', 'forUser'), Page::$messages['question']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$page = Page::create($data);

		// загрузка изображения
		$page->image = $page->setImage($data['image']);
		$page->content = $page->saveEditorImages($data['tempPath']);
		$page->save();

		// подписка на свой вопрос
		$subscription = new Subscription();
		$subscription->user_id = Auth::user()->id;
		$subscription->page_id = $page->id;
		$subscription->save();

		// добавление баллов, уведомления
		if($isPublished) {
			$page->user->addPoints(User::POINTS_FOR_QUESTION);
		}

		if($page->is_published) {
			$backUrl = URL::to($page->getUrl());
		} else {
			$backUrl = Input::has('backUrl')
				? Input::get('backUrl')
				: URL::route('user.questions', ['login' => $login]);
		}

		if ($isPublished) {
			return Redirect::to($backUrl)->with('successMessage', 'Ваш вопрос опубликован!');
		} else {
			return Redirect::to($backUrl)->with('infoMessage', 'Ваш вопрос создан и будет опубликован после проверки модератором.');
		}
	}

	public function editQuestion($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$question = Page::whereId($id)
			->whereUserId($user->id)
			->whereType(Page::TYPE_QUESTION)
			->firstOrFail();

		if(!$question->isEditable() && Auth::user()->isUser()) {
			return Response::view('errors.editable403', ['user' => $user], 403);
		}

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('user.questions', ['login' => $login]);

		View::share('user', $user);
		return View::make('cabinet::user.editQuestion', compact('question', 'backUrl'));
	}

	/**
	 * Предварительный просмотр
	 *
	 * @param $login
	 * @return \Illuminate\View\View
	 */
	public function preview($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$inputData = Input::get('formData');
		parse_str($inputData, $formFields);

		if(!Request::has('id')) {
			$page = new Page();
		} else {
			$page = Page::find(Request::get('id'));
		}

		$data = $formFields;
		$data['image'] = $data['image_url'];
		$data['user_id'] = $user->id;
		$data['content'] = StringHelper::nofollowLinks($data['content']);
		$data['published_at'] = \Carbon\Carbon::now();
		$data['alias'] = TranslitHelper::make($data['title']);

        if(isset($data['tags'])) {
            $newTag = isset($data['tags']['newTags']) ? $data['tags']['newTags'] : [];
            unset($data['tags']['newTags'], $data['tags']['new']);
            $tags = $newTag + $data['tags'];
        } else {
            $tags = [];
        }

		// validation
		$action = !Request::has('id') ? 'create' : 'update';
		$rules = Page::rules($action, 'forUser', $page->id);
		unset($rules['image']);
		if($data['type'] == Page::TYPE_ARTICLE) {
			unset($rules['parent_id']);
		}
		$validator = Validator::make($data, $rules, ($data['type'] == Page::TYPE_ARTICLE) ? Page::$messages['article'] : Page::$messages['question']);
		if ($validator->fails())
		{
			return Response::json(array(
				'fail' => true,
				'errors' => $validator->getMessageBag()->toArray(),
			));
		}

		$page->fill($data);

		return Response::json(array(
			'success' => true,
			'previewHtml' => (string) View::make('cabinet::user.preview', compact('page', 'tags'))->render(),
		));
	}

	public function updateQuestion($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$page = Page::whereId($id)
			->whereUserId($user->id)
			->whereType(Page::TYPE_QUESTION)
			->firstOrFail();

		$data = Input::all();
		$data['type'] = Page::TYPE_QUESTION;
		$data['user_id'] = $page->user->id;;
		$data['content'] = StringHelper::nofollowLinks($data['content']);
		$data['alias'] = TranslitHelper::make($data['title']);

		$validator = Validator::make($data, Page::rules('update', 'forUser', $page->id), Page::$messages['question']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		$data['image'] = $page->setImage($data['image']);

		$page->update($data);

		$page->content = $page->saveEditorImages($data['tempPath']);
		$page->save();

		$backUrl = Input::has('backUrl')
			? Input::get('backUrl')
			: URL::route('user.questions', ['login' => $login]);
		return Redirect::to($backUrl);
	}

	public function deleteQuestion($login)
	{
		if(Request::ajax())
		{
			$user = (Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
			$question = Page::whereId(Input::get('questionId'))
				->whereUserId($user->id)
				->whereType(Page::TYPE_QUESTION)
				->firstOrFail();

			if(!$question->isEditable() && Auth::user()->isUser()) {
				return Response::json([
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.danger', ['siteMessage' => 'Вы не можете удалить вопрос.'])
				]);
			}

			$pageTitle = $question->getTitle();
			$publishedStatusBeforeDelete = $question->id_published;
			if($question->delete()) {
				if($publishedStatusBeforeDelete) {
					$user->removePoints(User::POINTS_FOR_QUESTION);
					$user->setNotification(Notification::TYPE_QUESTION_DELETED, [
						'[pageTitle]' => $pageTitle,
					]);
				}
			}

			return Response::json([
				'success' => true,
				'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Вопрос удален.'])
			]);
		}
	}

	public function createJournal($login)
	{
		$article = new Page();
		$article->type = Page::TYPE_ARTICLE;
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $login]);

		View::share('user', $user);
		return View::make('cabinet::user.createJournal', compact('article', 'backUrl'));
	}

	public function storeJournal($login)
	{
		$premoderation = Setting::whereKey('articlesPremoderation')->select('value')->first();
		$isPublished = $premoderation->value ? 0 : 1;

		$data = Input::all();

		$data['type'] = Page::TYPE_ARTICLE;
		$data['parent_id'] = Page::whereType(Page::TYPE_JOURNAL)->first()->id;
		$data['user_id'] = Auth::user()->id;
		$data['content'] = StringHelper::nofollowLinks($data['content']);
		$data['is_published'] = $isPublished;
		$data['published_at'] = \Carbon\Carbon::now();
		$data['alias'] = TranslitHelper::make($data['title']);

		$validator = Validator::make($data, Page::rules('create', 'forUser'), Page::$messages['article']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$page = Page::create($data);

		// загрузка изображения
		$page->image = $page->setImage($data['image']);
		$page->content = $page->saveEditorImages($data['tempPath']);
		$page->save();

		// удаление тегов
		Tag::deleteTag($page, Input::get('tags'));
		// добавление тегов
		Tag::addTag($page, Input::get('tags'));

		// добавление баллов, уведомления
		if($isPublished) {
			$page->user->addPoints(User::POINTS_FOR_ARTICLE);
			$page->user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_ADDED, [
				'[pageTitle]' => $page->getTitle(),
				'[linkToPage]' => URL::to($page->getUrl())
			]);
		}
		if($page->is_published) {
			$backUrl = URL::to($page->getUrl());
		} else {
			$backUrl = Input::has('backUrl')
				? Input::get('backUrl')
				: URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $login]);
		}

		if ($isPublished) {
			return Redirect::to($backUrl)->with('successMessage', 'Статья в журнале опубликована!');
		} else {
			return Redirect::to($backUrl)->with('infoMessage', 'Ваша статья создана и будет опубликована после проверки модератором.');
		}
	}

	public function editJournal($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$article = Page::whereId($id)
			->whereUserId($user->id)
			->whereType(Page::TYPE_ARTICLE)
			->with('tags')
			->firstOrFail();

		if(!$article->isEditable() && Auth::user()->isUser()) {
			return Response::view('errors.editable403', ['user' => $user], 403);
		}

		$backUrl = Request::has('backUrl')
			? urldecode(Request::get('backUrl'))
			: URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $login]);

		View::share('user', $user);
		return View::make('cabinet::user.editJournal', compact('article', 'backUrl'));
	}

	public function updateJournal($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$page = Page::whereId($id)
			->whereUserId($user->id)
			->whereType(Page::TYPE_ARTICLE)
			->firstOrFail();

		$data = Input::all();

		$data['type'] = Page::TYPE_ARTICLE;
		$data['parent_id'] = Page::whereType(Page::TYPE_JOURNAL)->first()->id;
		$data['user_id'] = $page->user->id;
		$data['content'] = StringHelper::nofollowLinks($data['content']);
		$data['alias'] = TranslitHelper::make($data['title']);

		$validator = Validator::make($data, Page::rules('update', 'forUser', $page->id), Page::$messages['article']);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		$data['image'] = $page->setImage($data['image']);

		$page->update($data);

		$page->content = $page->saveEditorImages($data['tempPath']);
		$page->save();

		// удаление тегов
		Tag::deleteTag($page, Input::get('tags'));
		// добавление тегов
		Tag::addTag($page, Input::get('tags'));

		$backUrl = Input::has('backUrl')
			? Input::get('backUrl')
			: URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $login]);
		return Redirect::to($backUrl);
	}

	public function deleteJournal($login)
	{
		if(Request::ajax())
		{
			$user = (Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
			$article = Page::whereId(Input::get('articleId'))
				->whereUserId($user->id)
				->whereType(Page::TYPE_ARTICLE)
				->firstOrFail();
			if(!$article->isEditable() && Auth::user()->isUser()) {
				return Response::json([
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.danger', ['siteMessage' => 'Вы не можете удалить статью.'])
				]);
			}
			$pageTitle = $article->getTitle();
			$publishedStatusBeforeDelete = $article->is_published;
			if($article->delete()) {
				if($publishedStatusBeforeDelete) {
					$user->removePoints(User::POINTS_FOR_ARTICLE);
					$user->setNotification(Notification::TYPE_POINTS_FOR_ARTICLE_REMOVED, [
						'[pageTitle]' => $pageTitle,
					]);
				}
			}

			return Response::json([
				'success' => true,
				'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Статья удалена.'])
			]);
		}
	}

	/**
	 * Удаление изображения из таблицы Page
	 *
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteImageFromPage($login, $id) {
		if(Request::ajax())
		{
			$page = Page::findOrFail($id);
			$imageDirectory = public_path() . '/uploads/' . $page->getTable() . '/' . $page->id . '/';

			// delete old image with directory
			if(File::exists($imageDirectory)) {
				File::deleteDirectory($imageDirectory);
			}

			$page->image = null;
			$page->save();

			return Response::json([
				'success' => true,
				'message' => (string) View::make('widgets.siteMessages.success', ['siteMessage' => 'Изображение удалено.'])
			]);
		}
	}

	public function comments($login)
	{
		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail())
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		if(Auth::check()){
			if(Auth::user()->getLoginForUrl() == $login || Auth::user()->isAdmin()) {
				$comments = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
					->whereUserId($user->id)
					->whereIsAnswer(0)
					->whereIsDeleted(0)
					->with([
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedChildren' => function($query) {
							$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
						},
						'parent.user',
						'page' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
					])
					->orderBy('created_at', 'DESC')
					->paginate(10);
			} else {
				$comments = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
					->whereUserId($user->id)
					->whereIsPublished(1)
					->whereIsAnswer(0)
					->whereIsDeleted(0)
					->with([
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedChildren' => function($query) {
							$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
						},
						'parent.user',
						'page' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
					])
					->orderBy('created_at', 'DESC')
					->paginate(10);
			}
		} else {
			$comments = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
				->whereUserId($user->id)
				->whereIsPublished(1)
				->whereIsAnswer(0)
				->whereIsDeleted(0)
				->with([
					'user' => function($query) {
						$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
					},
					'publishedChildren' => function($query) {
						$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
					},
					'parent.user',
					'page' => function($query) {
						$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
					},
					'page.parent' => function($query) {
						$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
					},
					'page.parent.parent' => function($query) {
						$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
					},
				])
				->orderBy('created_at', 'DESC')
				->paginate(10);
		}

		View::share('user', $user);
		return View::make('cabinet::user.comments', compact('comments'));
	}

	public function editComment($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$comment = Comment::whereId($id)
			->whereUserId($user->id)
			->whereIsAnswer(0)
			->firstOrFail();

		if(!$comment->isEditable() && Auth::user()->isUser()) {
			return Response::view('errors.editable403', ['user' => $user], 403);
		}

		View::share('user', $user);
		return View::make('cabinet::user.editComment', compact('comment'));
	}

	public function editAnswer($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$comment = Comment::whereId($id)
			->whereUserId($user->id)
			->whereIsAnswer(1)
			->firstOrFail();

		if(!$comment->isEditable() && Auth::user()->isUser()) {
			return Response::view('errors.editable403', ['user' => $user], 403);
		}

		View::share('user', $user);
		return View::make('cabinet::user.editComment', compact('comment'));
	}

	public function updateComment($login, $id)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$comment = Comment::whereId($id)
			->whereUserId($user->id)
			->firstOrFail();

		$data = Input::all();

		$validator = Validator::make($data, Comment::$rulesForUpdate);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$comment->fill($data);
        $comment->comment = $comment->saveEditorImages($data['tempPath']);
        $comment->save();

		return Redirect::route(($comment->is_answer) ? 'user.answers' : 'user.comments', ['login' => $login]);
	}

	/**
	 * Удаление комментария
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteComment($login)
	{
		if(Request::ajax()) {
			$commentId = Input::get('commentId');

			$user = (Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

			$comment = Comment::find($commentId);
			if($comment) {
				$comment->sendNotificationsAboutDelete();
				$comment->delete();

				$comments = Comment::whereUserId($user->id)
					->whereIsAnswer(0)
					->whereIsDeleted(0)
					->with('page.parent.parent', 'user', 'publishedChildren', 'parent.user')
					->orderBy('created_at', 'DESC')
					->paginate(10);

				return Response::json(array(
					'success' => true,
					'newComments' => count($comments),
					'commentsList' => (string) View::make('cabinet::user.commentsList', compact('comments'))->with('user', $user)->render(),
					'message' => (string) View::make('widgets.siteMessages.success', [
						'siteMessage' => 'Комментарий удален.'
					]),
				));
			} else {
				return Response::json(array(
					'success' => false,
				));
			}
		}
	}

	public function answers($login)
	{
		$user = Auth::check()
			? ((Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail())
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		if(Auth::check()){
			if(Auth::user()->is($user) || Auth::user()->isAdmin()) {
				$answers = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
					->whereUserId($user->id)
					->whereIsAnswer(1)
					->with([
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedChildren' => function($query) {
							$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
						},
						'page' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
					])
					->orderBy('created_at', 'DESC')
					->paginate(10);
			} else {
				$answers = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
					->whereUserId($user->id)
					->whereIsPublished(1)
					->whereIsAnswer(1)
					->with([
						'user' => function($query) {
							$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
						},
						'publishedChildren' => function($query) {
							$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
						},
						'page' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
						'page.parent.parent' => function($query) {
							$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
						},
					])
					->orderBy('created_at', 'DESC')
					->paginate(10);
			}
		} else {
			$answers = Comment::select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at')
				->whereUserId($user->id)
				->whereIsPublished(1)
				->whereIsAnswer(1)
				->with([
					'user' => function($query) {
						$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
					},
					'publishedChildren' => function($query) {
						$query->select('id', 'is_answer', 'parent_id', 'user_id', 'ip_id', 'user_email', 'user_name', 'page_id', 'is_published', 'votes_like', 'votes_dislike', 'comment', 'mark', 'created_at');
					},
					'page' => function($query) {
						$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
					},
					'page.parent' => function($query) {
						$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
					},
					'page.parent.parent' => function($query) {
						$query->select('id', 'alias', 'parent_id', 'user_id', 'is_container', 'type', 'title');
					},
				])
				->orderBy('created_at', 'DESC')
				->paginate(10);
		}

		View::share('user', $user);
		return View::make('cabinet::user.answers', compact('answers'));
	}

	/**
	 * Удаление ответа
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteAnswer($login)
	{
		if(Request::ajax()) {
			$answerId = Input::get('answerId');

			$user = (Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

			$answer = Comment::find($answerId);
			if($answer) {
				$answer->sendNotificationsAboutDelete();
				$answer->delete();

				$answers = Comment::whereUserId($user->id)
					->whereIsAnswer(1)
					->whereIsDeleted(0)
					->with('page.parent.parent', 'user', 'publishedChildren', 'parent.user')
					->orderBy('created_at', 'DESC')
					->paginate(10);

				return Response::json(array(
					'success' => true,
					'newAnswers' => count($answers),
					'answersList' => (string) View::make('cabinet::user.answersList', compact('answers'))->with('user', $user)->render(),
					'message' => (string) View::make('widgets.siteMessages.success', [
						'siteMessage' => 'Ответ удален.'
					]),
				));
			} else {
				return Response::json(array(
					'success' => false,
				));
			}
		}
	}

	public function messages($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$companions = User::whereHas('sentMessages', function($q) use ($user)
			{
				$q->where('user_id_recipient', '=', $user->id);
			})
			->orWhereHas('receivedMessages', function($q) use ($user)
			{
				$q->where('user_id_sender', '=', $user->id);
			})
			->with('sentMessagesForUser')
			->get();

		$messages = Message::from(DB::raw('(select * from messages where user_id_recipient = ' . $user->id . ' order by created_at DESC) t'))
			->groupBy('user_id_sender')
			->with('userSender')
			->get();

		View::share('user', $user);
		return View::make('cabinet::user.messages', compact('companions', 'messages'));
	}

	public function dialog($login, $companion)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$companion = User::whereAlias($companion)->firstOrFail();

		/*
		Для вывода переписки с отдельным пользователем
		SELECT * FROM `messages` WHERE ((user_id_sender = 2 OR user_id_recipient = 2) AND (user_id_sender = 1 OR user_id_recipient = 1))
			ORDER BY created_at DESC
		*/
		$messages = Message::query()
			->whereNested(function($q) use ($user) {
				$q->where('user_id_sender', $user->id)->orWhere('user_id_recipient', $user->id);
			})
			->whereNested(function($q) use ($companion) {
				$q->where('user_id_sender', $companion->id)->orWhere('user_id_recipient', $companion->id);
			})
			->with('userSender', 'userRecipient')
			->orderBy('created_at', 'DESC')
			->get();
//			->paginate(5);

		$companions = User::whereHas('sentMessages', function($q) use ($user)
			{
				$q->where('user_id_recipient', '=', $user->id);
			})
			->orWhereHas('receivedMessages', function($q) use ($user)
			{
				$q->where('user_id_sender', '=', $user->id);
			})
			->with('sentMessagesForUser')
			->get();

		View::share('user', $user);
		return View::make('cabinet::user.dialog', compact('companion', 'messages', 'companions'));
	}

	/**
	 * Отметить сообщение как прочитанное
	 */
	public function markMessageAsRead($login)
	{
		if(Request::ajax()) {

			$user = (Auth::user()->getLoginForUrl() == $login)
				? Auth::user()
				: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

			$messageId = Input::get('messageId');

			$message = Message::find($messageId);
			$message->read_at = date('Y:m:d H:i:s');

			if ($message->save())
			{
				// сброс кэша
				Cache::forget('headerWidget.newMessages.' . Auth::user()->id);

				$messages = Message::whereUserIdRecipient($user->id)
					->whereNull('read_at')
					->orderBy('created_at', 'DESC')
					->get();

				return Response::json(array(
					'success' => true,
					'newMessages' => count($messages),
				));
			}
		}
	}

    public function reloadMessages($login, $companion)
    {
        if(Request::ajax()) {
            $user = (Auth::user()->getLoginForUrl() == $login)
                ? Auth::user()
                : User::whereAlias($login)->whereIsActive(1)->firstOrFail();
            $companion = User::whereAlias($companion)->firstOrFail();

            $messages = Message::query()
                ->whereNested(function($q) use ($user) {
                    $q->where('user_id_sender', $user->id)->orWhere('user_id_recipient', $user->id);
                })
                ->whereNested(function($q) use ($companion) {
                    $q->where('user_id_sender', $companion->id)->orWhere('user_id_recipient', $companion->id);
                })
                ->with('userSender', 'userRecipient')
                ->orderBy('created_at', 'DESC');

            return Response::json(array(
                'success' => true,
                'messagesListHtml' => (string) View::make('cabinet::user.messagesList', compact('companion', 'user'))->with('messages', $messages->get())->render(),
	            'newMessage' => count($messages->whereUserIdSender($companion->id)->whereNull('read_at')->get()),
	            'allNewMessages' => count(Message::whereUserIdRecipient(Auth::user()->id)->whereNull('read_at')->get()),
            ));
        }
    }

	/**
	 * Отправить личное сообщение
	 *
	 * @param $login
	 * @param $companionId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addMessage($login, $companionId)
	{
		if(Request::ajax()) {

			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);

			$messageData = array(
				'user_id_sender' => Auth::user()->id,
				'user_id_recipient' => $companionId,
				'message' => StringHelper::nofollowLinks($formFields['message']),
			);

			$validator = Validator::make($messageData, Message::$rules);

			if ($validator->fails())
				return Response::json(array(
					'fail' => true,
					'errors' => $validator->getMessageBag()->toArray()
				));
			else {
				//save to DB user details
				if ($message = Message::create($messageData)) {
                    $message->message = $message->saveEditorImages($formFields['tempPath']);
                    $message->save();

					// сброс кэша
					Cache::forget('headerWidget.newMessages.' . $companionId);

					// отметить предыдущие сообщения как прочитанные
					$unreadMessages = Message::whereUserIdSender($companionId)
						->whereUserIdRecipient(Auth::user()->id)
						->whereNull('read_at');
					$countUnreadMessages = count($unreadMessages->get());
					$unreadMessages->update([
						'read_at' => \Carbon\Carbon::now(),
					]);

					//return success message
					return Response::json(array(
						'success' => true,
						'messageId' => $message->id,
						'newMessageHtml' => (string) View::make('cabinet::user.newMessage', compact('message'))->render(),
						'countUnreadMessages' => $countUnreadMessages,
					));
				}
			}
		}
	}

	public function savedPages($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$pages = UserPage::whereUserId($user->id)
			->with('page.parent.parent', 'page.user', 'page.whoSaved', 'page.publishedAnswers', 'page.publishedComments', 'page.bestComments', 'page.subscribers', 'page.tags')
			->orderBy('created_at', 'DESC')
			->paginate(10);
		View::share('user', $user);

		return View::make('cabinet::user.savedPages', compact('pages'));
	}

	public function savePage($login)
	{
		if(Request::ajax()) {
			$pageId = Input::get('pageId');
			$page = Page::whereId($pageId)->first();

			if(!Auth::user()->hasInSaved($pageId)) {
				$userPage = new UserPage();
				$userPage->user_id = Auth::user()->id;
				$userPage->page_id = $pageId;

				if($userPage->save()) {
					return Response::json(array(
						'success' => true,
						'message' => (string) View::make('widgets.siteMessages.success', [
							'siteMessage' => 'Страница сохранена в <a href="'. URL::route('user.savedPages', ['login' => Auth::user()->getLoginForUrl()]) .'">cохраненное</a>.'
						]),
						'whoSaved' => count($page->whoSaved)
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', [
						'siteMessage' => 'Страница уже сохранена.'
					]),
				));
			}
		}
	}

	public function removePage($login)
	{
		if(Request::ajax()) {
			$pageId = Input::get('pageId');
			$page = Page::whereId($pageId)->first();

			if(UserPage::whereUserId(Auth::user()->id)->wherePageId($pageId)->first()) {
				UserPage::whereUserId(Auth::user()->id)->wherePageId($pageId)->delete();
				return Response::json(array(
					'success' => true,
					'message' => (string) View::make('widgets.siteMessages.success', [
						'siteMessage' => 'Страница удалена из <a href="'. URL::route('user.savedPages', ['login' => Auth::user()->getLoginForUrl()]) .'">сохраненного</a>.'
					]),
					'whoSaved' => count($page->whoSaved)
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', [
						'siteMessage' => 'Страница уже удалена из <a href="'. URL::route('user.savedPages', ['login' => Auth::user()->getLoginForUrl()]) .'">сохраненного</a>.'
					]),
				));
			}
		}
	}

	public function removeAllPages()
	{
		if(Request::ajax()) {

			$savedPages = UserPage::whereUserId(Auth::user()->id);

			if($savedPages->count()) {
				$savedPages->delete();

				return Response::json(array(
					'success' => true,
					'message' => (string) View::make('widgets.siteMessages.success', [
						'siteMessage' => 'Все страницы удалены из сохраненного.'
					]),
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', [
						'siteMessage' => 'У вас нет сохраненных страниц.'
					]),
				));
			}
		}
	}

	public function subscriptions($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();
		$subscriptions = Subscription::whereUserId($user->id)
			->with('page.parent.parent', 'page.user', 'page.whoSaved', 'page.publishedAnswers', 'page.bestComments', 'page.subscribers', 'page.tags', 'notifications')
			->orderBy('created_at', 'DESC')
			->paginate(10);

		View::share('user', $user);
		return View::make('cabinet::user.subscriptions', compact('subscriptions'));
	}

	public function subscribe()
	{
		if(Request::ajax()) {
			$subscriptionObjectId = Input::get('subscriptionObjectId');
			$subscriptionField = Input::get('subscriptionField');

			if(!Auth::user()->subscribed($subscriptionObjectId, $subscriptionField)) {
				$subscription = new Subscription();
				$subscription->user_id = Auth::user()->id;
				if(Subscription::FIELD_PAGE_ID == $subscriptionField) {
					$subscription->page_id = $subscriptionObjectId;
				} else {
					$subscription->journal_id = $subscriptionObjectId;
				}

				if($subscription->save()) {
					if(Subscription::FIELD_PAGE_ID == $subscriptionField) {
						if($subscription->page) {
							$subscription->page->user->setNotification(Notification::TYPE_SUBSCRIBED_ON_QUESTION, [
								'[user]' => $subscription->user->login,
								'[linkToUser]' => URL::route('user.profile', ['login' => $subscription->user->getLoginForUrl()]),
								'[pageTitle]' => $subscription->page->getTitle(),
								'[linkToPage]' => URL::to($subscription->page->getUrl()),
							]);
						}
						return Response::json(array(
							'success' => true,
							'message' => (string) View::make('widgets.siteMessages.success', [
								'siteMessage' => 'Подписка оформлена.'
							]),
							'subscribers' => count($subscription->page->subscribers),
						));
					} else {
						if($subscription->userJournal) {
							$subscription->userJournal->setNotification(Notification::TYPE_SUBSCRIBED_ON_JOURNAL, [
								'[user]' => $subscription->user->login,
								'[linkToUser]' => URL::route('user.profile', ['login' => $subscription->user->getLoginForUrl()]),
							]);
						}
						return Response::json(array(
							'success' => true,
							'message' => (string) View::make('widgets.siteMessages.success', [
								'siteMessage' => 'Подписка оформлена.'
							]),
							'subscribers' => count($subscription->userJournal->subscribers),
						));
					}
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', [
						'siteMessage' => 'Подписка уже оформлена.'
					]),
				));
			}
		}
	}

	public function unsubscribe()
	{
		if(Request::ajax()) {
			$subscriptionObjectId = Input::get('subscriptionObjectId');
			$subscriptionField = Input::get('subscriptionField');

			$subscription = Subscription::whereUserId(Auth::user()->id)
				->where($subscriptionField, '=', $subscriptionObjectId)
				->first();

			if($subscription) {
				$subscription->delete();
				if(Subscription::FIELD_PAGE_ID == $subscriptionField) {
					$page = Page::find(Input::get('subscriptionObjectId'));
					if($page) {
						$page->user->setNotification(Notification::TYPE_UNSUBSCRIBED_FROM_QUESTION, [
							'[user]' => $subscription->user->login,
							'[linkToUser]' => URL::route('user.profile', ['login' => $subscription->user->getLoginForUrl()]),
							'[pageTitle]' => $subscription->page->getTitle(),
							'[linkToPage]' => URL::to($subscription->page->getUrl()),
						]);
					}
					return Response::json(array(
						'success' => true,
						'message' => (string) View::make('widgets.siteMessages.success', [
							'siteMessage' => 'Подписка отменена.'
						]),
						'subscribers' => ($page) ? count($page->subscribers) : '',
					));
				} elseif(Subscription::FIELD_JOURNAL_ID == $subscriptionField) {
					$userJournal = User::find(Input::get('subscriptionObjectId'));
					if($userJournal) {
						$userJournal->setNotification(Notification::TYPE_UNSUBSCRIBED_FROM_JOURNAL, [
							'[user]' => $subscription->user->login,
							'[linkToUser]' => URL::route('user.profile', ['login' => $subscription->user->getLoginForUrl()]),
						]);
					}
					return Response::json(array(
						'success' => true,
						'message' => (string) View::make('widgets.siteMessages.success', [
							'siteMessage' => 'Подписка отменена.'
						]),
						'subscribers' => ($userJournal) ? count($userJournal->subscribers) : '',
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', [
						'siteMessage' => 'Подписка уже отменена.'
					]),
				));
			}
		}
	}

	/**
	 * Отписаться от всего
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function unsubscribeFromAll()
	{
		if(Request::ajax()) {

			$subscriptions = Subscription::whereUserId(Auth::user()->id);

			if($subscriptions->count()) {
				$deletedSubscriptions = $subscriptions->get();
				$subscriptions->delete();

				foreach($deletedSubscriptions as $subscription) {
					if($subscription->onPage()) {
						$page = Page::find($subscription->page_id);
						if($page) {
							$page->user->setNotification(Notification::TYPE_UNSUBSCRIBED_FROM_QUESTION, [
								'[user]' => $subscription->user->login,
								'[linkToUser]' => URL::route('user.profile', ['login' => $subscription->user->getLoginForUrl()]),
								'[pageTitle]' => $subscription->page->getTitle(),
								'[linkToPage]' => URL::to($subscription->page->getUrl()),
							]);
						}
 					} elseif($subscription->onJournal()) {
						$userJournal = User::find($subscription->journal_id);
						if($userJournal) {
							$userJournal->setNotification(Notification::TYPE_UNSUBSCRIBED_FROM_JOURNAL, [
								'[user]' => $subscription->user->login,
								'[linkToUser]' => URL::route('user.profile', ['login' => $subscription->user->getLoginForUrl()]),
							]);
						}
					}
				}

				return Response::json(array(
					'success' => true,
					'message' => (string) View::make('widgets.siteMessages.success', [
						'siteMessage' => 'Все подписки отменены.'
					]),
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.success', [
						'siteMessage' => 'У вас нет подписок.'
					]),
				));
			}
		}
	}

	/**
	 * Удаление оповещения по подпискам
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteSubscriptionNotification()
	{
		if(Request::ajax()) {
			$notificationId = Input::get('notificationId');

			if($notification = SubscriptionNotification::find($notificationId)) {
				$notification->delete();

				// сброс кэша
				Cache::forget('headerWidget.newSubscriptionsNotifications.' . Auth::user()->id);

				return Response::json(array(
					'success' => true,
				));
			} else {
				return Response::json(array(
					'success' => false,
				));
			}
		}
	}

	public function notifications($login)
	{
		$user = (Auth::user()->getLoginForUrl() == $login)
			? Auth::user()
			: User::whereAlias($login)->whereIsActive(1)->firstOrFail();

		$notifications = Notification::whereUserId($user->id)
			->with('user')
			->orderBy('created_at', 'DESC')
			->orderBy('id', 'DESC')
			->paginate(10);

		View::share('user', $user);

		return View::make('cabinet::user.notifications', compact('notifications'));
	}

	/**
	 * Удаление уведомления
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteNotification()
	{
		if(Request::ajax()) {
			$notificationId = Input::get('notificationId');

			if($notification = Notification::find($notificationId)) {
				$notification->delete();

				$notifications = Notification::whereUserId(Auth::user()->id)
					->with('user')
					->orderBy('created_at', 'DESC')
					->orderBy('id', 'DESC')
					->paginate(10);

				// сброс кэша
				Cache::forget('headerWidget.newNotifications.' . Auth::user()->id);

				return Response::json(array(
					'success' => true,
					'newNotifications' => count($notifications),
					'notificationsList' => (string) View::make('cabinet::user.notificationsList', compact('notifications'))->with('user', Auth::user())->render()
				));
			} else {
				return Response::json(array(
					'success' => false,
				));
			}
		}
	}

	public function deleteAllNotifications()
	{
		if(Request::ajax()) {

			$notifications = Notification::whereUserId(Auth::user()->id);

			if($notifications->count()) {
				$notifications->delete();

				// сброс кэша
				Cache::forget('headerWidget.newNotifications.' . Auth::user()->id);

				return Response::json(array(
					'success' => true,
					'message' => (string) View::make('widgets.siteMessages.success', [
						'siteMessage' => 'Все уведомления удалены.'
					]),
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => (string) View::make('widgets.siteMessages.warning', [
						'siteMessage' => 'У вас нет уведомлений.'
					]),
				));
			}
		}
	}
}
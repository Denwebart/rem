<?php


class CabinetUserController extends \BaseController
{
	public function __construct()
	{
		$headerWidget = app('HeaderWidget');
		View::share('headerWidget', $headerWidget);

		$this->beforeFilter(function()
		{
			$login = Route::current()->getParameter('login');
			if(Auth::user()->getLoginForUrl() != $login && !Auth::user()->isAdmin()) {
				App::abort(403, 'Unauthorized action.');
			}

		}, ['except' => ['index', 'gallery', 'questions', 'comments', 'subscriptions']]);
	}

	public function index($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.index');
	}

	public function edit($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.edit');
	}

	/**
	 * Обновление профиля
	 *
	 * @param $login
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postEdit($login)
	{
		$user = User::whereLogin($login)->firstOrFail();

		$data = Input::all();

		$validator = Validator::make($data, $user->getValidationRules());

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['avatar'])){

			$fileName = TranslitHelper::generateFileName($data['avatar']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $user->getTable() . '/' . $data['login'] . '/';
			$image = Image::make($data['avatar']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			if($image->width() > 225) {
				$image->save($imagePath . 'origin_' . $fileName)
					->resize(225, null, function ($constraint) {
						$constraint->aspectRatio();
					})
					->save($imagePath . $fileName);
			} else {
				$image->save($imagePath . $fileName);
			}
			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();

			$image->crop($cropSize, $cropSize)
				->resize(50, null, function ($constraint) {
					$constraint->aspectRatio();
			})->save($imagePath . 'mini_' . $fileName);

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

			$user->avatar = $fileName;
		}
		// загрузка изображения

		$data['description'] = StringHelper::nofollowLinks($data['description']);
		$user->update($data);

		return Redirect::route('user.profile', ['login' => $user->login]);
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
			$user = User::whereLogin($login)->firstOrFail();
			$imagePath = public_path() . '/uploads/' . $user->getTable() . '/' . $user->login . '/';

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
				'imageUrl' => Config::get('settings.defaultAvatar'),
			]);
		}
	}

	public function gallery($login)
	{
		$user = User::whereLogin($login)->firstOrFail();

		View::share('user', $user);
		return View::make('cabinet::user.gallery');
	}

	public function uploadPhoto($login)
	{
		$user = User::whereLogin($login)->firstOrFail();
		$data = Input::all();
		$data['user_id'] = $user->id;
		$validator = Validator::make($data, UserImage::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$usersImage = new UserImage();

		// загрузка изображения
		if(isset($data['image'])) {
			$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $usersImage->getTable() . '/' . $user->login . '/';
			$image = Image::make($data['image']->getRealPath());

			File::exists(public_path() . '/uploads/' . $usersImage->getTable()) or File::makeDirectory(public_path() . '/uploads/' . $usersImage->getTable());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			$image->save($imagePath . $fileName);

			// delete old image
			if (File::exists($imagePath . $usersImage->image)) {
				File::delete($imagePath . $usersImage->image);
			}
			$data['image'] = $fileName;
		}
		// загрузка изображения

		$usersImage->fill($data);
		$usersImage->save();

		return Redirect::route('user.gallery', ['login' => $user->login]);

	}

	public function deletePhoto($login)
	{
		if(Request::ajax())
		{
			$image = UserImage::findOrFail(Input::get('imageId'));
			$imagePath = public_path() . '/uploads/' . $image->getTable() . '/' . $login . '/';

			if(File::exists($imagePath . $image->image)) {
				File::delete($imagePath . $image->image);
			}

			$image->delete();

			return Response::json([
				'success' => true,
			]);
		}
	}

	public function editPhoto($login, $id)
	{
		$user = User::whereLogin($login)->firstOrFail();

		$usersImage = UserImage::findOrFail($id);

		if($data = Input::all()) {
			$data['user_id'] = $user->id;
			$validator = Validator::make($data, UserImage::$rulesEdit);

			if ($validator->fails())
			{
				return Redirect::back()->withErrors($validator)->withInput();
			}

			// загрузка изображения
			if(isset($data['image'])) {
				$fileName = TranslitHelper::generateFileName($data['image']->getClientOriginalName());

				$imagePath = public_path() . '/uploads/' . $usersImage->getTable() . '/' . $user->login . '/';

				$image = Image::make($data['image']->getRealPath());

				File::exists($imagePath) or File::makeDirectory($imagePath);

				$image->save($imagePath . $fileName);

				// delete old image
				if (File::exists($imagePath . $usersImage->image)) {
					File::delete($imagePath . $usersImage->image);
				}
				$data['image'] = $fileName;
			} else {
				$data['image'] = is_null($data['image']) ? $usersImage->image : $data['image'];
			}
			// загрузка изображения

			$data['description'] = StringHelper::nofollowLinks($data['description']);

			if($usersImage->update($data)) {
				return Redirect::route('user.gallery', ['login' => $user->login]);
			}
		}

		return View::make('cabinet::user.galleryEdit', compact('user'))->with('image', $usersImage);
	}

	public function questions($login)
	{
		$user = User::whereLogin($login)->firstOrFail();

		if(Auth::user()->getLoginForUrl() == $login) {
			$questions = Page::whereType(Page::TYPE_QUESTION)
				->whereUserId($user->id)
				->paginate(10);
		} else {
			$questions = Page::whereType(Page::TYPE_QUESTION)
				->whereUserId($user->id)
				->whereIsPublished(1)
				->paginate(10);
		}
		View::share('user', $user);
		return View::make('cabinet::user.questions', compact('questions'));
	}

	public function createQuestion($login)
	{
		$question = new Page();

		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.createQuestion', compact('question'));
	}

	public function storeQuestion()
	{
		$data = Input::all();

		$data['type'] = Page::TYPE_QUESTION;
		$data['user_id'] = Auth::user()->id;
		$data['content'] = StringHelper::nofollowLinks($data['content']);

		$validator = Validator::make($data, Page::$rulesForUsers);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Page::create($data);

		return Redirect::route('user.questions', ['login' => Auth::user()->getLoginForUrl()]);
	}

	public function editQuestion($login, $id)
	{
		$question = Page::findOrFail($id);

		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.editQuestion', compact('question'));
	}

	public function updateQuestion($login, $id)
	{
		$page = Page::findOrFail($id);

		$data = Input::all();

		$data['type'] = Page::TYPE_QUESTION;
		$data['user_id'] = Auth::user()->id;
		$data['content'] = StringHelper::nofollowLinks($data['content']);

		$validator = Validator::make($data, Page::$rulesForUsers);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$page->update($data);

		return Redirect::route('user.questions', ['login' => $login]);
	}

	public function deleteQuestion($login)
	{
		if(Request::ajax())
		{
			$question = Page::findOrFail(Input::get('questionId'));

			$question->delete();

			return Response::json([
				'success' => true,
			]);
		}
	}

	public function journal($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.journal');
	}

	public function comments($login)
	{
		View::share('user', User::whereLogin($login)->with('comments')->firstOrFail());
		return View::make('cabinet::user.comments');
	}

	public function messages($login)
	{
		$user = User::whereLogin($login)->firstOrFail();

		/*
		 Для вывода последнего сообщения конкретного пользователя
		SELECT * FROM `messages` WHERE ((user_id_sender = 2 OR user_id_recipient = 2) AND (user_id_sender = 1 OR user_id_recipient = 1))
			ORDER BY created_at DESC
		*/

		$companions = User::whereHas('sentMessages', function($q) use ($user)
			{
				$q->where('user_id_recipient', '=', $user->id);
			})
			->orWhereHas('receivedMessages', function($q) use ($user)
			{
				$q->where('user_id_sender', '=', $user->id);
			})
			->get();

		View::share('user', $user);
		return View::make('cabinet::user.messages', compact('companions'));
	}

	public function dialog($login, $companion)
	{
		$user = User::whereLogin($login)->firstOrFail();
		$companion = User::whereLogin($companion)->firstOrFail();

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
			->with(['userSender', 'userRecipient'])
			->orderBy('created_at', 'ASC')
			->get();

		$companions = User::whereHas('sentMessages', function($q) use ($user)
		{
			$q->where('user_id_recipient', '=', $user->id);
		})
			->orWhereHas('receivedMessages', function($q) use ($user)
			{
				$q->where('user_id_sender', '=', $user->id);
			})
			->get();

		View::share('user', $user);
		return View::make('cabinet::user.dialog', compact(['companion', 'messages', 'companions']));
	}

	/**
	 * Отметить сообщение как прочитанное
	 */
	public function markMessageAsRead()
	{
		if(Request::ajax()) {

			$messageId = Input::get('messageId');

			$message = Message::find($messageId);
			$message->read_at = date('Y:m:d H:i:s');

			if ($message->save())
			{
				$messages = Message::whereUserIdRecipient(Auth::user()->id)
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

	/**
	 * Отправить личное сообщение
	 */
	public function addMessage($id)
	{
		if(Request::ajax()) {

			$inputData = Input::get('formData');
			parse_str($inputData, $formFields);

			$messageData = array(
				'user_id_sender' => Auth::user()->id,
				'user_id_recipient' => $id,
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
					//return success message
					return Response::json(array(
						'success' => true,
						'message' => $message->message,
						'messageId' => $message->id,
						'messageCreadedAt' => DateHelper::dateForMessage($message->created_at),
					));
				}
			}
		}
	}

	public function savedPages($login)
	{
		$user = User::whereLogin($login)->with('savedPages')->firstOrFail();
		$pages = UserPage::whereUserId($user->id)
			->orderBy('created_at', 'DESC')
			->paginate(10);
		View::share('user', $user);

		return View::make('cabinet::user.savedPages', compact('pages'));
	}

	public function savePage($login)
	{
		if(Request::ajax()) {
			$pageId = Input::get('pageId');

			if(!Auth::user()->hasInSaved($pageId)) {
				$userPage = new UserPage();
				$userPage->user_id = Auth::user()->id;
				$userPage->page_id = $pageId;

				if($userPage->save()) {
					return Response::json(array(
						'success' => true,
						'message' => 'Страница сохранена.'
					));
				}
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Страница уже сохранена.'
				));
			}
		}
	}

	public function removePage($login)
	{
		if(Request::ajax()) {
			$pageId = Input::get('pageId');

			if(UserPage::whereUserId(Auth::user()->id)->wherePageId($pageId)->first()) {
				UserPage::whereUserId(Auth::user()->id)->wherePageId($pageId)->delete();
				return Response::json(array(
					'success' => true,
					'message' => 'Страница удалена из сохраненных.'
				));
			} else {
				return Response::json(array(
					'success' => false,
					'message' => 'Страница уже удалена из сохраненных.'
				));
			}
		}
	}

	public function subscriptions($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.subscriptions');
	}
}
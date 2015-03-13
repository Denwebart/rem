<?php


class CabinetUserController extends \BaseController
{
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
	 * @param $userId
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postEdit($userId)
	{
		$user = User::findOrFail($userId);

		$data = Input::all();

		$validator = Validator::make($data, $user->getValidationRules());

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		// загрузка изображения
		if(isset($data['avatar'])){

			$fileName = TranslitHelper::generateFileName($data['avatar']->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $data['login'] . '/';
			$image = Image::make($data['avatar']->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath);

			if(225 < $image->width()) {
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

		$user->update($data);

		return Redirect::route('user.profile', ['login' => $user->login]);
	}

	/**
	 * Удаление изображения
	 *
	 * @param $userId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteAvatar($userId) {
		if(Request::ajax())
		{
			$user = User::findOrFail($userId);
			$imagePath = public_path() . '/uploads/' . $user->login . '/';

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
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.gallery');
	}

	public function questions($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.questions');
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
		SELECT * FROM
		(select * from `messages` WHERE user_id_sender = 2 OR user_id_recipient = 2 ORDER BY `created_at` DESC) t
		GROUP BY user_id_sender, user_id_recipient ORDER BY `created_at` DESC
		*/

		/*
		 Для вывода последнего сообщения конкретного пользователя
		SELECT * FROM `messages` WHERE ((user_id_sender = 2 OR user_id_recipient = 2) AND (user_id_sender = 1 OR user_id_recipient = 1))
			ORDER BY created_at DESC
		*/

//		$companions = User::with(['sentMessages', 'receivedMessages'])
//			->where()
//			->orderBy('created_at', 'DESC')
//			->get();

//		$dialogs = Message::where(function($query) use ($user){
//			$query->from('messages')->whereUserIdSender($user->id)
//				->orWhere('user_id_recipient', $user->id)
//				->orderBy('created_at', 'DESC');
//		})->groupBy(['user_id_sender', 'user_id_recipient'])->orderBy('created_at', 'DESC')->get();

//		echo '<pre>';
//		dd($companions);

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
			->orderBy('created_at', 'DESC')
			->get();

		View::share('user', $user);
		return View::make('cabinet::user.dialog', compact(['companion', 'messages']));
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
				return Response::json(array(
					'success' => true,
				));
			}
		}
	}

	public function friends($login)
	{
		View::share('user', User::whereLogin($login)->firstOrFail());
		return View::make('cabinet::user.friends');
	}
}
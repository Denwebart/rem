<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * User
 *
 * @property integer $id 
 * @property string $login 
 * @property string $alias 
 * @property string $email 
 * @property string $firstname 
 * @property string $lastname 
 * @property boolean $role 
 * @property integer $points 
 * @property string $avatar 
 * @property string $description 
 * @property string $car_brand 
 * @property string $profession 
 * @property string $city 
 * @property string $country 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property boolean $is_active 
 * @property boolean $is_banned 
 * @property boolean $is_agree 
 * @property string $activationCode 
 * @property string $remember_token 
 * @property string $password 
 * @property string $last_activity 
 * @property boolean $is_online 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ip[] $ips 
 * @property-read \UserSetting $settings 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Message[] $receivedMessages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Message[] $sentMessages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Message[] $sentMessagesForUser 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $comments 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $publishedComments 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $answers 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $publishedAnswers 
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserImage[] $images 
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserImage[] $publishedImages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $questions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $publishedQuestions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $articles 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $publishedArticles 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $savedPages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Subscription[] $subscriptions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Subscription[] $subscribers 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Honor[] $honors 
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserHonor[] $userHonors 
 * @property-read \Illuminate\Database\Eloquent\Collection|\BanNotification[] $banNotifications 
 * @property-read \Illuminate\Database\Eloquent\Collection|\BanNotification[] $latestBanNotification 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Notification[] $notifications 
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereFirstname($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLastname($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePoints($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCarBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereProfession($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIsBanned($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIsAgree($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereActivationCode($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLastActivity($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIsOnline($value)
 */
class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'users';

	const POINTS_FOR_COMMENT = 1;
	const POINTS_FOR_ANSWER = 1;
	const POINTS_FOR_BEST_ANSWER = 4;
	const POINTS_FOR_QUESTION = 0;
	const POINTS_FOR_ARTICLE = 5;

	const ROLE_NONE = 0;
	const ROLE_ADMIN = 1;
	const ROLE_MODERATOR = 2;
	const ROLE_USER = 3;

	public static $roles = [
		self::ROLE_ADMIN => 'Администратор',
		self::ROLE_MODERATOR => 'Модератор',
		self::ROLE_USER => 'Пользователь',
	];

	const INTERVAL_ALL_TIMES = 'all_times';
	const INTERVAL_MONTH = 'month';
	const INTERVAL_YEAR = 'year';

	public static $intervals = [
		self::INTERVAL_ALL_TIMES => 'За все время',
		self::INTERVAL_MONTH => 'За месяц',
		self::INTERVAL_YEAR => 'За год',
	];

	protected $fillable = [
		'login',
		'alias',
		'email',
		'firstname',
		'lastname',
		'role',
		'avatar',
		'description',
		'car_brand',
		'profession',
		'city',
		'country',
	];

	public function getValidationRules()
	{
		return [
			'email' => 'required|email|unique:users,email,' . $this->id . '|max:150',
			'firstname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'lastname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'role' => 'integer',
			'points' => 'integer',
			'avatar' => 'mimes:jpeg,bmp,png|max:2048',
			'description' => 'max:3000',
			'car_brand' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ0-9 \-\']+$/u',
			'profession' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'city' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'country' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'is_active' => 'boolean',
			'is_agree' => 'boolean',
			'is_banned' => 'boolean',
		];
	}

	public static $rules = [
		'registration' => [
			'email'       => 'required|email|unique:users|max:150',
			'login'       => 'required|unique:users|max:150|regex:/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ\-\']+$/u',
			'alias'       => 'unique:users',
			'password'    => 'required|confirmed|min:6|max:100',
			'g-recaptcha-response' => 'required|captcha'
		],
		'login' => [
			'login'       => 'required_without_all:email',
			'email'       => 'required_without_all:login|email',
			'password'    => 'required',
//			'g-recaptcha-response' => 'required|captcha'
		],
		'create' => [
			'password'    => 'required|confirmed|min:6|max:100',
			'email'       => 'required|email|unique:users|max:150',
			'login'       => 'required|unique:users|max:150|regex:/^[0-9A-Za-zА-Яа-яЁёЇїІіЄєЭэ\-\']+$/u',
			'alias'       => 'required|unique:users|max:150|regex:/^[A-Za-z0-9\-]+$/',
			'firstname'   => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'lastname'    => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'role'        => 'integer',
			'points'      => 'integer',
			'avatar'      => 'mimes:jpeg,bmp,png|max:3072',
			'description' => 'max:3000',
			'car_brand'   => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ0-9 \-\']+$/u',
			'profession'  => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'city'        => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'country'     => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'is_active'   => 'boolean',
			'is_agree'    => 'boolean',
		],
		'update' => [
			'email'       => 'required|email|unique:users,email,:id|max:150',
			'firstname'   => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'lastname'    => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'role'        => 'integer',
			'points'      => 'integer',
			'avatar'      => 'mimes:jpeg,bmp,png|max:3072',
			'description' => 'max:3000',
			'car_brand'   => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ0-9 \-\']+$/u',
			'profession'  => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'city'        => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'country'     => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
			'is_active'   => 'boolean',
			'is_agree'    => 'boolean',
			'is_banned'   => 'boolean',
		],
	];

	public static function boot()
	{
		parent::boot();

		static::created(function($letter)
		{
			// очистка кэша
			Cache::forget('headerWidget.newUsers');
		});

		static::deleted(function($user)
		{
			// очистка кэша
			Cache::forget('headerWidget.newUsers');

			File::deleteDirectory(public_path() . '/uploads/' . $user->getTable() . '/' . $user->getLoginForUrl() . '/');
		});

		static::deleting(function($user) {
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
			if(count($user->publishedArticles)) {
				Cache::forget('widgets.latest');
				Cache::forget('widgets.best');
				Cache::forget('widgets.notBest');
				Cache::forget('widgets.popular');
				Cache::forget('widgets.unpopular');
			}
			// сохранение комментариев при удалении пользователя
			foreach($user->allComments as $comment) {
				$comment->user_name = $user->login;
				$comment->user_email = $user->email;
				$comment->user_id = null;
				$comment->save();
			}
			// удаление статей и вопросов после удаления пользователя
			foreach($user->questions as $question) {
				$question->delete();
			}
			foreach($user->articles as $article) {
				$article->delete();
			}
		});
	}

	public function register()
	{
		$this->password = Hash::make($this->password);
		$this->activationCode = $this->generateCode();
		$this->is_active = false;
		$this->login = StringHelper::mbUcFirst($this->login);
		$this->alias = TranslitHelper::make($this->login);
		$this->role = self::ROLE_NONE;

		$this->save();

		$this->setIp(Request::ip());

		Log::info("User [{$this->email}] registered. Activation code: {$this->activationCode}");

		$this->sendActivationMail();

		return $this->id;
	}

	protected function generateCode()
	{
		return Str::random(); // По умолчанию длина случайной строки 16 символов
	}

	public function sendActivationMail()
	{
		$activationUrl = action(
			'UsersController@getActivate',
			[
				'userId' => $this->id,
				'activationCode'    => $this->activationCode,
			]
		);

		$template = EmailTemplate::whereKey('activation')->first();
		$variables = [
			'[siteUrl]' => Config::get('app.url'),
			'[activationUrl]' => $activationUrl,
		];
		$content = strtr($template->html, $variables);

		$that = $this;
		Mail::queue('layouts.email', ['content' => $content, 'userModel' => $that], function($message) use ($that, $template)
		{
			$siteEmail = ($siteEmailModel = Setting::whereKey('siteEmail')->whereIsActive(1)->first())
				? $siteEmailModel->value
				: Config::get('settings.adminEmail');
			$message->from($siteEmail, Config::get('settings.adminName'));
			$message->to($that->email)->subject($template->subject);
		});

		Log::info("Mail to user [{$this->username}] has been sent. Activation url: {$activationUrl}");
	}

	public function activate($activationCode)
	{
		// Если пользователь уже активирован, не будем делать никаких
		// проверок и вернем false
		if ($this->is_active) {
			return false;
		}

		// Если коды не совпадают, то также ввернем false
		if ($activationCode != $this->activationCode) {
			return false;
		}

		// Обнулим код, назначим роль, изменим флаг isActive и сохраним
		$this->activationCode = '';
		$this->is_active = true;
		$this->role = self::ROLE_USER;
		$this->save();

		// И запишем информацию в лог, просто, чтобы была :)
		Log::info("User [{$this->email}] successfully activated");

		return true;
	}

	public function is($user)
	{
		return ($user) ? (($user->id == $this->id) ? true : false) : false;
	}

	public function hasRole()
	{
		return (self::ROLE_NONE != $this->role) ? true : false;
	}

	public function isAdmin()
	{
		return (self::ROLE_ADMIN == $this->role) ? true : false;
	}

	public function isModerator()
	{
		return (self::ROLE_MODERATOR == $this->role) ? true : false;
	}

	public function isUser()
	{
		return (self::ROLE_USER == $this->role) ? true : false;
	}

	public function getLoginForUrl()
	{
		return ($this->alias) ? $this->alias : TranslitHelper::make($this->login);
	}

	public function getFullName()
	{
		$separator = ($this->firstname && $this->lastname) ? ' ' : '';
		return $this->firstname . $separator . $this->lastname;
	}

	/**
	 * Получение изображения
	 *
	 * @param null $prefix
	 * @param array $options
	 * @param boolean $tooltip
	 * @return string
	 */
	public function getAvatar($prefix = null, $options = [], $tooltip = true)
	{
		$alt = $this->id
			? ($this->getFullName()
				? $this->login . ' ('. $this->getFullName() .')'
				: $this->login)
			: 'Незарегистрированный пользователь';
		$options['title'] = $alt;
		if($tooltip) {
			$options['data-toggle'] = 'tooltip';
			$options['data-placement'] = isset($options['data-placement']) ? $options['data-placement'] : 'bottom';
		}
		if(isset($options['class'])) {
			$options['class'] = ($this->avatar) ? 'img-responsive ' . $options['class'] : 'img-responsive avatar-default ' . $options['class'];
		} else {
			$options['class'] = ($this->avatar) ? 'img-responsive' : 'img-responsive avatar-default';
		}
		$prefix = is_null($prefix) ? '' : ($prefix . '_');
		if($this->avatar){
			return HTML::image($this->getAvatarUrl($prefix), $alt, $options);
		} else {
			return HTML::image(Config::get('settings.' . $prefix . 'defaultAvatar'), $alt, $options);
		}
	}

	/**
	 * Получение URL изображения
	 *
	 * @param null $prefix
	 * @return string
	 */
	public function getAvatarUrl($prefix = null)
	{
        return '/uploads/' . $this->getTable() . '/' . $this->getLoginForUrl() . '/' . $prefix . $this->avatar;
	}

	/**
	 * Загрузка изображения
	 *
	 * @param $postImage
	 * @return mixed|string
	 */
	public function setAvatar($postImage)
	{
		if(isset($postImage)){

			$fileName = TranslitHelper::generateFileName($postImage->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $this->getTable() . '/' . $this->getLoginForUrl() . '/';
			$image = Image::make($postImage->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			// delete old avatar
			if(File::exists($imagePath . $this->avatar)) {
				File::delete($imagePath . $this->avatar);
			}
			if(File::exists($imagePath . 'origin_' . $this->avatar)){
				File::delete($imagePath . 'origin_' . $this->avatar);
			}
			if(File::exists($imagePath . 'mini_' . $this->avatar)){
				File::delete($imagePath . 'mini_' . $this->avatar);
			}

			if(Config::get('settings.maxImageWidth') && $image->width() > Config::get('settings.maxImageWidth')) {
				$image->resize(Config::get('settings.maxImageWidth'), null, function ($constraint) {
					$constraint->aspectRatio();
				});
			}
			if(Config::get('settings.maxImageHeight') && $image->height() > Config::get('settings.maxImageHeight')) {
				$image->resize(null, Config::get('settings.maxImageHeight'), function ($constraint) {
					$constraint->aspectRatio();
				});
			}
			$image->save($imagePath . 'origin_' . $fileName);

			if($image->width() > 260) {
				$image->resize(260, null, function ($constraint) {
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

			return $fileName;
		} else {
			return $this->avatar;
		}
	}

    /**
     * Перемещение изображений из временной папки
     *
     * @param $tempPath
     * @return mixed
     */
    public function saveEditorImages($tempPath)
    {
        $moveDirectory = File::copyDirectory(public_path($tempPath), public_path($this->getImageEditorPath()));
        if($moveDirectory) {
            File::deleteDirectory(public_path($tempPath));
        }
	    return str_replace($tempPath, $this->getImageEditorPath(), $this->description);
    }

    /**
     * Получение временного пути для загрузки изображения
     *
     * @return string
     */
    public function getTempPath() {
        return '/uploads/temp/' . Str::random(20) . '/';
    }

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->getLoginForUrl() . '/editor/';
	}

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getMessageImagePath() {
		return '/uploads/' . (new Message)->getTable() . '/' . $this->getLoginForUrl() . '/';
	}

	/**
	 * Ip-адреса пользователя
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function ips()
	{
		return $this->belongsToMany('Ip', 'users_ips');
	}

	/**
	 * Настройки аккаунта пользователя
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function settings()
	{
		return $this->hasOne('UserSetting', 'user_id');
	}

	public function setIp($ip)
	{
		$ipModel = Ip::whereIp($ip)->first();
        if(is_null($ipModel)) {
	        $ipModel = Ip::create(['ip' => $ip]);
        }
		if(is_null(UserIp::whereIpId($ipModel->id)->whereUserId($this->id)->first())) {
			UserIp::create([
				'user_id' => $this->id,
				'ip_id' => $ipModel->id,
			]);
		}
	}

	/**
	 * Полученные сообщения
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function receivedMessages()
	{
		return $this->hasMany('Message', 'user_id_recipient');
	}

	/**
	 * Отправленные сообщения
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function sentMessages()
	{
		return $this->hasMany('Message', 'user_id_sender');
	}

	/**
	 * Отправленные сообщения для конкретного пользователя
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function sentMessagesForUser()
	{
		return $this->hasMany('Message', 'user_id_sender')
			->whereNull('read_at')
			->where('user_id_recipient', '=', Auth::user()->id);
	}

	/**
	 * Все ответы и комментарии
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function allComments()
	{
		return $this->hasMany('Comment', 'user_id');
	}

	/**
	 * Оставленные комментарии
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function comments()
	{
		return $this->hasMany('Comment', 'user_id')->whereIsAnswer(0);
	}

	/**
	 * Опубликованные комментарии
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function publishedComments()
	{
		return $this->hasMany('Comment', 'user_id')
			->whereIsPublished(1)
			->whereIsAnswer(0)
			->select(['id', 'is_answer', 'is_published']);
	}

	/**
	 * Ответы
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function answers()
	{
		return $this->hasMany('Comment', 'user_id')->whereIsAnswer(1);
	}

	/**
	 * Опубликованные ответы
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function publishedAnswers()
	{
		return $this->hasMany('Comment', 'user_id')
			->whereIsAnswer(1)
			->whereIsPublished(1)
			->select(['id', 'is_answer', 'is_published']);
	}

	/**
	 * Лучшие опубликованные ответы
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function bestPublishedAnswers()
	{
		return $this->hasMany('Comment', 'user_id')
			->whereIsAnswer(1)
			->whereMark(Comment::MARK_BEST)
			->whereIsPublished(1)
			->select(['id', 'is_answer', 'is_published']);
	}

	/**
	 * Изображения пользователя ("Мои автомобили")
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function images()
	{
		return $this->hasMany('UserImage', 'user_id');
	}

	/**
	 * Опубликованные изображения пользователя ("Мои автомобили")
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function publishedImages()
	{
		return $this->hasMany('UserImage', 'user_id')->whereIsPublished(1);
	}

	/**
	 * Вопросы пользователя ("Вопрос-ответ")
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function questions()
	{
		return $this->hasMany('Page', 'user_id')->whereType(Page::TYPE_QUESTION);
	}

	public function publishedQuestions()
	{
		return $this->hasMany('Page', 'user_id')->whereType(Page::TYPE_QUESTION)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->select(['id', 'type', 'is_published', 'published_at']);
	}

	/**
	 * Статьи пользователя ("Бортовой журнал")
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function articles()
	{
		return $this->hasMany('Page', 'user_id')->whereType(Page::TYPE_ARTICLE);
	}

	public function publishedArticles()
	{
		return $this->hasMany('Page', 'user_id')->whereType(Page::TYPE_ARTICLE)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'))
			->select(['id', 'type', 'is_published', 'published_at']);
	}

	/**
	 * Сохраненные страницы пользователя ("Сохраненное")
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function savedPages()
	{
		return $this->belongsToMany('Page', 'users_pages');
	}

	/**
	 * Подписки пользователя ("Подписки")
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subscriptions()
	{
		return $this->hasMany('Subscription', 'user_id');
	}

	/**
	 * Подписчики пользователя (подписка на журнал)
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subscribers()
	{
		return $this->hasMany('Subscription', 'journal_id');
	}

	/**
	 * Награды пользователя
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function honors()
	{
		return $this->belongsToMany('Honor', 'users_honors')
			->select(['honors.id', 'user_id', 'title', 'honors.alias', 'image']);
	}

	public function userHonors()
	{
		return $this->hasMany('UserHonor', 'user_id')
			->select(['user_id', 'honor_id', 'comment']);
	}

	/**
	 * Сообщения о бане (дата и причина бана пользователя)
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function banNotifications()
	{
		return $this->hasMany('BanNotification', 'user_id')->orderBy('ban_at', 'DESC');
	}

	public function latestBanNotification()
	{
		return $this->hasOne('BanNotification', 'user_id')->orderBy('ban_at', 'DESC');
	}

	/**
	 * Уведомления пользователя
	 *
	 * @return mixed
	 */
	public function notifications()
	{
		return $this->hasMany('Notification', 'user_id')->orderBy('created_at', 'DESC');
	}

	public function setBanNotification($message)
	{
		BanNotification::create([
			'user_id' => $this->id,
			'message' => $message,
			'ban_at' => date('Y:m:d H:i:s'),
		]);
	}

	public function setNotification($notificationType, $variables = [])
	{
		$notification = new Notification();
		$notification->add($this, $notificationType, $variables);
	}

	/**
	 * Есть ли страница в сохраненных пользователем
	 *
	 * @return bool
	 */
    public function hasInSaved($pageId)
    {
	    return (Auth::user()->savedPages()->wherePageId($pageId)->first()) ? true : false;
    }

	/**
	 * Есть ли страница в подписках пользователя
	 *
	 * @return bool
	 */
	public function subscribed($subscriptionObjectId, $subscriptionField)
	{
		return (Auth::user()->subscriptions()->where($subscriptionField, '=', $subscriptionObjectId)->first())
			? true
			: false;
	}

	/**
	 * Начисление баллов пользователю
	 *
	 * @param $points
	 *  Возможные значения $points:
	 *   self::POINTS_FOR_COMMENT,
	 *   self::POINTS_FOR_ANSWER,
	 *   self::POINTS_FOR_GOOD_ANSWER,
	 *   self::POINTS_FOR_BEST_ANSWER,
	 *   self::POINTS_FOR_QUESTION,
	 *   self::POINTS_FOR_ARTICLE.
	 */
	public function addPoints($points) {
		$this->points = $this->points + $points;
		$this->save();
	}

	public function removePoints($points) {
		$this->points = $this->points - $points;
		$this->save();
	}

	public function setLastActivity()
	{
		if(!Cache::has('user.lastActivity.' . Auth::user()->id)) {
			$this->last_activity = \Carbon\Carbon::now();
			$this->save();
			Cache::put('user.lastActivity.' . Auth::user()->id, $this->last_activity, Config::get('settings.userActivityTime'));
		}
	}

	public function setOnline($is_online)
	{
		$this->is_online = $is_online;
		$this->save();
	}

	public function isOnline()
	{
		if(0 == $this->is_online) {
			return false;
		} else {
			return ($this->last_activity < \Carbon\Carbon::now()->subMinutes(Config::get('settings.userActivityTime')))
				? false
				: true;
		}
	}

	/**
	 * Лучший писатель прошедшего месяца
	 * (по баллам за статьи)
	 *
	 * @param null $year
	 * @param null $month
	 * @param int $limit
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getBestWriter($year = null, $month = null, $limit = 3)
	{
		if(is_null($month)) {
			$lastMonth = date_create(date('d-m-Y') . ' first day of last month');
			$month = $lastMonth->format('m');
			$year = $lastMonth->format('Y');
		}

		return User::leftJoin('pages', 'pages.user_id', '=', 'users.id')
			->select([DB::raw('users.id, users.login, users.alias, users.last_activity, users.is_online, users.firstname, users.lastname, users.is_online, users.avatar, count(pages.id) AS articlesCount, count(pages.id) * '. User::POINTS_FOR_ARTICLE .' as articlesPoints')])
			->where('pages.type', '=', Page::TYPE_ARTICLE)
			->whereBetween('pages.published_at', [date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year)), date('Y-m-d H:i:s', mktime(23, 59, 59, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year), $year))])
			->where('users.role', '=', User::ROLE_USER)
			->where('users.is_banned', '=', 0)
			->groupBy('users.id')
			->orderBy('articlesPoints', 'DESC')
			->orderBy('articlesCount', 'DESC')
			->limit($limit)
			->get();
	}

	/**
	 * Лучший писатель прошедшего года
	 * (по баллам за статьи)
	 *
	 * @param null $year
	 * @param int $limit
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getBestWriterOfYear($year = null, $limit = 3)
	{
		if(is_null($year)) {
			$lastYear = date_create(date('d-m-Y') . ' first day of last year');
			$year = $lastYear->format('Y');
		}

		return User::leftJoin('pages', 'pages.user_id', '=', 'users.id')
			->select([DB::raw('users.id, users.login, users.alias, users.last_activity, users.is_online, users.firstname, users.lastname, users.is_online, users.avatar, count(pages.id) AS articlesCount, count(pages.id) * '. User::POINTS_FOR_ARTICLE .' as articlesPoints')])
			->where('pages.type', '=', Page::TYPE_ARTICLE)
			->whereBetween('pages.published_at', [date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, $year)), date('Y-m-d H:i:s', mktime(23, 59, 59, 12, cal_days_in_month(CAL_GREGORIAN, 12, $year), $year))])
			->where('users.role', '=', User::ROLE_USER)
			->where('users.is_banned', '=', 0)
			->groupBy('users.id')
			->orderBy('articlesPoints', 'DESC')
			->orderBy('articlesCount', 'DESC')
			->limit($limit)
			->get();
	}

	/**
	 * Лучший советчик прошедшего месяца
	 * (по баллам за ответы)
	 *
	 * @param null $year
	 * @param null $month
	 * @param int $limit
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getBestRespondent($year = null, $month = null, $limit = 3)
	{
		if(is_null($month)) {
			$lastMonth = date_create(date('d-m-Y') . ' first day of last month');
			$month = $lastMonth->format('m');
			$year = $lastMonth->format('Y');
		}

		return User::leftJoin('comments', 'comments.user_id', '=', 'users.id')
			->select([DB::raw('users.id, users.login, users.alias, users.last_activity, users.is_online, users.firstname, users.lastname, users.is_online, users.avatar, count(comments.id) AS answersCount, SUM(IF((comments.votes_like - comments.votes_dislike) >= 0, 1, 0)) * '. User::POINTS_FOR_ANSWER .' + SUM(IF(comments.mark = 1, 1, 0)) * '. User::POINTS_FOR_BEST_ANSWER .' as answersPoints, SUM(IF(comments.mark = 1, 1, 0)) as countBestAnswers')])
			->where('comments.is_answer', '=', 1)
			->whereBetween('comments.created_at', [date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year)), date('Y-m-d H:i:s', mktime(23, 59, 59, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year), $year))])
			->where('users.role', '=', User::ROLE_USER)
			->where('users.is_banned', '=', 0)
			->groupBy('users.id')
			->orderBy('answersPoints', 'DESC')
			->orderBy('countBestAnswers', 'DESC')
			->orderBy('answersCount', 'DESC')
			->limit($limit)
			->get();
	}

	/**
	 * Лучший советчик прошедшего года
	 * (по баллам за ответы)
	 *
	 * @param null $year
	 * @param int $limit
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getBestRespondentOfYear($year = null, $limit = 3)
	{
		if(is_null($year)) {
			$lastYear = date_create(date('d-m-Y') . ' first day of last year');
			$year = $lastYear->format('Y');
		}

		return User::leftJoin('comments', 'comments.user_id', '=', 'users.id')
			->select([DB::raw('users.id, users.login, users.alias, users.last_activity, users.is_online, users.firstname, users.lastname, users.is_online, users.avatar, count(comments.id) AS answersCount, SUM(IF((comments.votes_like - comments.votes_dislike) >= 0, 1, 0)) * '. User::POINTS_FOR_ANSWER .' + SUM(IF(comments.mark = 1, 1, 0)) * '. User::POINTS_FOR_BEST_ANSWER .' as answersPoints, SUM(IF(comments.mark = 1, 1, 0)) as countBestAnswers')])
			->where('comments.is_answer', '=', 1)
			->whereBetween('comments.created_at', [date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, $year)), date('Y-m-d H:i:s', mktime(23, 59, 59, 12, cal_days_in_month(CAL_GREGORIAN, 12, $year), $year))])
			->where('users.role', '=', User::ROLE_USER)
			->where('users.is_banned', '=', 0)
			->groupBy('users.id')
			->orderBy('answersPoints', 'DESC')
			->orderBy('countBestAnswers', 'DESC')
			->orderBy('answersCount', 'DESC')
			->limit($limit)
			->get();
	}

	/**
	 * Лучший комментатор прошедшего месяца
	 * (по баллам за комментарии)
	 *
	 * @param null $year
	 * @param null $month
	 * @param int $limit
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getBestCommentator($year = null, $month = null, $limit = 3)
	{
		if(is_null($month)) {
			$lastMonth = date_create(date('d-m-Y') . ' first day of last month');
			$month = $lastMonth->format('m');
			$year = $lastMonth->format('Y');
		}

		return User::leftJoin('comments', 'comments.user_id', '=', 'users.id')
			->select([DB::raw('users.id, users.login, users.alias, users.last_activity, users.is_online, users.firstname, users.lastname, users.is_online, users.avatar, count(comments.id) AS commentsCount, SUM(IF((comments.votes_like - comments.votes_dislike) >= 0, 1, 0)) * '. User::POINTS_FOR_COMMENT .' as commentsPoints')])
			->where('comments.is_answer', '=', 0)
			->whereBetween('comments.created_at', [date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year)), date('Y-m-d H:i:s', mktime(23, 59, 59, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year), $year))])
			->where('users.role', '=', User::ROLE_USER)
			->where('users.is_banned', '=', 0)
			->groupBy('users.id')
			->orderBy('commentsPoints', 'DESC')
			->orderBy('commentsCount', 'DESC')
			->limit($limit)
			->get();
	}

	/**
	 * Лучший комментатор прошедшего года
	 * (по баллам за комментарии)
	 *
	 * @param null $year
	 * @param int $limit
	 * @return array|\Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getBestCommentatorOfYear($year = null, $limit = 3)
	{
		if(is_null($year)) {
			$lastYear = date_create(date('d-m-Y') . ' first day of last year');
			$year = $lastYear->format('Y');
		}

		return User::leftJoin('comments', 'comments.user_id', '=', 'users.id')
			->select([DB::raw('users.id, users.login, users.alias, users.last_activity, users.is_online, users.firstname, users.lastname, users.is_online, users.avatar, count(comments.id) AS commentsCount, SUM(IF((comments.votes_like - comments.votes_dislike) >= 0, 1, 0)) * '. User::POINTS_FOR_COMMENT .' as commentsPoints')])
			->where('comments.is_answer', '=', 0)
			->whereBetween('comments.created_at', [date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, $year)), date('Y-m-d H:i:s', mktime(23, 59, 59, 12, cal_days_in_month(CAL_GREGORIAN, 12, $year), $year))])
			->where('users.role', '=', User::ROLE_USER)
			->where('users.is_banned', '=', 0)
			->groupBy('users.id')
			->orderBy('commentsPoints', 'DESC')
			->orderBy('commentsCount', 'DESC')
			->limit($limit)
			->get();
	}
}

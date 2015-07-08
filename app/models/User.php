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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Message[] $receivedMessages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Message[] $sentMessages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Message[] $sentMessagesForUser 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $comments 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $publishedComments 
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserImage[] $images 
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserImage[] $publishedImages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $questions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $publishedQuestions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $articles 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $publishedArticles 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $savedPages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Subscription[] $subscriptions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Honor[] $honors 
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLogin($value)
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
 */

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'users';

	const POINTS_FOR_COMMENT = 1;
	const POINTS_FOR_ANSWER = 1;
	const POINTS_FOR_GOOD_ANSWER = 1;
	const POINTS_FOR_BEST_ANSWER = 4;
	const POINTS_FOR_QUESTION = 3;
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

	protected $fillable = [
		'login',
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
			'email' => 'required|email|unique:users,id,' . $this->id . '|max:150',
			'firstname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'lastname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'role' => 'integer',
			'points' => 'integer',
			'avatar' => 'mimes:jpeg,bmp,png|max:3072',
			'description' => 'max:3000',
			'car_brand' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє0-9 \-\']+$/u',
			'profession' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'city' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'country' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'is_active' => 'boolean',
			'is_agree' => 'boolean',
			'is_banned' => 'boolean',
		];
	}

	public static $rules = [
		'registration' => [
			'email'     => 'required|email|unique:users|max:150',
			'login'  => 'required|unique:users|max:150|regex:/^[A-Za-z0-9\-]+$/',
			'password'  => 'required|confirmed|min:6|max:100',
		],
		'login' => [
			'login'  => 'required',
			'password'  => 'required',
		],
		'create' => [
			'password'  => 'required|confirmed|min:6|max:100',
			'email' => 'required|email|unique:users|max:150',
			'login' => 'required|unique:users|max:150|regex:/^[A-Za-z0-9\-]+$/',
			'firstname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'lastname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'role' => 'integer',
			'points' => 'integer',
			'avatar' => 'mimes:jpeg,bmp,png|max:3072',
			'description' => 'max:3000',
			'car_brand' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє0-9 \-\']+$/u',
			'profession' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'city' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'country' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'is_active' => 'boolean',
			'is_agree' => 'boolean',
		]
	];

	public static function boot()
	{
		parent::boot();

		static::deleted(function($user)
		{
			File::deleteDirectory(public_path() . '/uploads/' . $user->getTable() . '/' . $user->login . '/');
		});
	}

	public function register()
	{
		$this->password = Hash::make($this->password);
		$this->activationCode = $this->generateCode();
		$this->is_active = false;
		$this->login = ucfirst($this->login);
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
			array(
				'userId' => $this->id,
				'activationCode'    => $this->activationCode,
			)
		);

		$that = $this;
		Mail::send('emails/activation',
			array('activationUrl' => $activationUrl),
			function ($message) use($that) {
				$message->to($that->email)->subject('Спасибо за регистрацию!');
			}
		);

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
		return strtolower($this->login);
	}

	public function getFullName()
	{
		$separator = ($this->firstname && $this->lastname) ? ' ' : '';
		return $this->firstname . $separator . $this->lastname;
	}

	public function getAvatar($prefix = null, $options = [])
	{
		if(isset($options['class'])) {
			$options['class'] = ($this->avatar) ? 'img-responsive ' . $options['class'] : 'img-responsive avatar-default ' . $options['class'];
		} else {
			$options['class'] = ($this->avatar) ? 'img-responsive' : 'img-responsive avatar-default';
		}
		$prefix = is_null($prefix) ? '' : ($prefix . '_');
		if($this->avatar){
			return HTML::image('/uploads/' . $this->getTable() . '/' . $this->login . '/' . $prefix . $this->avatar, $this->login, $options);
		} else {
			return HTML::image(Config::get('settings.' . $prefix . 'defaultAvatar'), $this->login, $options);
		}
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
	 * Оставленные комментарии
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function comments()
	{
		return $this->hasMany('Comment', 'user_id');
	}

	/**
	 * Опубликованные комментарии
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function publishedComments()
	{
		return $this->hasMany('Comment', 'user_id')->whereIsPublished(1);
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
		return $this->hasMany('Comment', 'user_id')->whereIsAnswer(1)->whereIsPublished(1);
	}

	/**
	 * Изображения пользователя ("Мой автомобиль")
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function images()
	{
		return $this->hasMany('UserImage', 'user_id');
	}

	/**
	 * Опубликованные изображения пользователя ("Мой автомобиль")
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
			->where('published_at', '<', date('Y-m-d H:i:s'));
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
			->where('published_at', '<', date('Y-m-d H:i:s'));
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
	 * Награды пользователя
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function honors()
	{
		return $this->belongsToMany('Honor', 'users_honors');
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
//		return $this->hasMany('BanNotification', 'user_id')->latest('ban_notifications.ban_at');
	}

	public function setBanNotification($message)
	{
		BanNotification::create([
			'user_id' => $this->id,
			'message' => $message,
			'ban_at' => date('Y:m:d H:i:s'),
		]);
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
	public function subscribed($pageId)
	{
		return (Auth::user()->subscriptions()->wherePageId($pageId)->first()) ? true : false;
	}

//	public static function getWhoHaveNoHonor($honorId)
//	{
//		$list = self::whereDoesntHave('honors')
//			->orWhereHas('honors', function($query) use($honorId) {
//				$query->where('honor_id', '!=', $honorId);
//			})->lists('login', 'id');
//	}

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

}

<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

/**
 * User
 *
 * @property integer $id
 * @property integer $user_id
 * @property boolean $is_published
 * @property string $login
 * @property string $email
 * @property string $firstname
 * @property string $lastname
 * @property integer $role
 * @property string $ip
 * @property string $description
 * @property string $car_brand
 * @property string $profession
 * @property string $city
 * @property string $country
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIsPublished($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLogin($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereFirstname($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereLastname($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCarBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereProfession($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value)
 * @property boolean $is_active
 * @property string $activationCode
 * @property string $remember_token
 * @method static \Illuminate\Database\Query\Builder|\User whereIsActive($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereActivationCode($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value) 
 */

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'users';

	const ROLE_NONE = 0;
	const ROLE_ADMIN = 1;
	const ROLE_MANAGER = 2;
	const ROLE_USER = 3;

	public static $roles = [
		self::ROLE_ADMIN => 'Администратор',
		self::ROLE_MANAGER => 'Модератор',
		self::ROLE_USER => 'Пользователь',
	];

	protected $fillable = [
		'user_id',
		'is_active',
		'name',
		'firstname',
		'lastname',
		'email',
		'password',
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
			'login' => 'required|unique:users,id,' . $this->id . '|max:150|regex:/^[A-Za-z0-9\-]+$/',
			'firstname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'lastname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'role' => 'integer',
			'avatar' => 'mimes:jpeg,bmp,png|max:3072',
			'description' => 'max:3000',
			'car_brand' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє0-9 \-\']+$/u',
			'profession' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'city' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'country' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'is_active' => 'boolean',
		];
	}

	public static $rules = [
		'registration' => [
			'email'     => 'required|email|unique:users|max:150',
			'login'  => 'required|unique:users|max:150|regex:/^[A-Za-z0-9\-]+$/',
			'password'  => 'required|confirmed|min:6|max:100',
		],
		'create' => [
			'password'  => 'required|confirmed|min:6|max:100',
			'email' => 'required|email|unique:users|max:150',
			'login' => 'required|unique:users|max:150|regex:/^[A-Za-z0-9\-]+$/',
			'firstname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'lastname' => 'max:100|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'role' => 'integer',
			'avatar' => 'mimes:jpeg,bmp,png|max:3072',
			'description' => 'max:3000',
			'car_brand' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє0-9 \-\']+$/u',
			'profession' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'city' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'country' => 'max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u',
			'is_active' => 'boolean',
		]
	];

	public function register()
	{
		$this->password = Hash::make($this->password);
		$this->activationCode = $this->generateCode();
		$this->is_active = false;
		$this->save();

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

		// Обнулим код, изменим флаг isActive и сохраним
		$this->activationCode = '';
		$this->is_active = true;
		$this->save();

		// И запишем информацию в лог, просто, чтобы была :)
		Log::info("User [{$this->email}] successfully activated");

		return true;
	}

	public function is($user)
	{
		return ($user->id == $this->id) ? true : false;
	}

	public function hasRole()
	{
		return (self::ROLE_NONE != $this->role) ? true : false;
	}

	public function isAdmin()
	{
		return (self::ROLE_ADMIN == $this->role) ? true : false;
	}

	public function isManager()
	{
		return (self::ROLE_MANAGER == $this->role) ? true : false;
	}

	public function isUser()
	{
		return (self::ROLE_USER == $this->role) ? true : false;
	}

	public function getFullName()
	{
		$separator = ($this->firstname && $this->lastname) ? ' ' : '';
		return $this->firstname . $separator . $this->lastname;
	}

	public function getAvatar($prefix = null, $options = [])
	{
		$class = isset($options['class']) ? ' ' . $options['class'] : '';
		$prefix = is_null($prefix) ? '' : ($prefix . '_');
		if($this->avatar){
			return HTML::image('/uploads/' . $this->getTable() . '/' . $this->login . '/' . $prefix . $this->avatar, $this->login, ['class' => 'img-responsive' . $class]);
		} else {
			return HTML::image(Config::get('settings.' . $prefix . 'defaultAvatar'), $this->login, ['class' => 'img-responsive avatar-default' . $class]);
		}
	}

	/**
	 * Отправленные сообщения
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function receivedMessages()
	{
		return $this->hasMany('Message', 'user_id_recipient');
	}

	/**
	 * Полученные сообщения
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function sentMessages()
	{
		return $this->hasMany('Message', 'user_id_sender');
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
	public function publishedСomments()
	{
		return $this->hasMany('Comment', 'user_id')->whereIsPublished(1);
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


}

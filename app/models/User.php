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
 * @property string $name
 * @property string $email
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
 * @method static \Illuminate\Database\Query\Builder|\User whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereIp($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereDescription($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCarBrand($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereProfession($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCity($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCountry($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value) 
 */

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	protected $fillable = [
		'user_id',
		'is_published',
		'name',
		'email',
		'description',
		'car_brand',
		'profession',
		'city',
		'country',
	];

}

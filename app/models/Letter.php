<?php

/**
 * Letter
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_ip
 * @property string $user_name
 * @property string $user_email
 * @property string $subject
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $read_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\Letter whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Letter whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereUserIp($value)
 * @method static \Illuminate\Database\Query\Builder|\Letter whereUserName($value)
 * @method static \Illuminate\Database\Query\Builder|\Letter whereUserEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Letter whereSubject($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereMessage($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereReadAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereDeletedAt($value)
 */

class Letter extends \Eloquent
{
	protected $table = 'letters';

	protected $fillable = [
		'user_id',
		'user_name',
		'user_email',
		'user_ip',
		'subject',
		'message',
		'read_at',
		'deleted_at',
	];

	public static $rules = [
		'user_id' => 'required_without_all:user_name,user_email|numeric',
		'user_ip' => 'ip',
		'user_name' => 'required_without_all:user_id|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄє \-\']+$/u|min:3',
		'user_email' => 'required_without_all:user_id|email',
		'subject' => 'max:500',
		'message' => 'required|min:5',
		'g-recaptcha-response' => 'required|captcha'
	];

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
}
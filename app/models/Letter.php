<?php

/**
 * Letter
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $ip
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $read_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\Letter whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Letter whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereIp($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Letter whereEmail($value) 
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
		'name',
		'email',
		'subject',
		'message',
		'read_at',
		'deleted_at',
	];
}
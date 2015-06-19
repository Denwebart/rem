<?php

/**
 * Ip
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property string $ip 
 * @method static \Illuminate\Database\Query\Builder|\Ip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereIp($value)
 */

class Ip extends \Eloquent
{
	protected $table = 'ips';

	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'ip',
	];

	public static $rules = [
		'user_id' => 'numeric',
		'ip' => 'required|ip',
	];

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}
}
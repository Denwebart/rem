<?php


/**
 * Ip
 *
 * @property integer $id 
 * @property string $ip 
 * @property boolean $is_banned 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserIp[] $usersIps 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $users 
 * @method static \Illuminate\Database\Query\Builder|\Ip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereIsBanned($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereUpdatedAt($value)
 */
class Ip extends \Eloquent
{
	protected $table = 'ips';

	public $timestamps = false;

	protected $fillable = [
		'ip',
		'is_banned',
		'ban_at',
		'unban_at',
	];

	public static $rules = [
		'ip' => 'required|ip',
		'is_banned' => 'boolean',
	];

	public function usersIps()
	{
		return $this->hasMany('UserIp', 'ip_id');
	}

	public function users()
	{
		return $this->belongsToMany('User', 'users_ips');
	}
}
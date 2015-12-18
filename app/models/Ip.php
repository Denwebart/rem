<?php

/**
 * Ip
 *
 * @property integer $id 
 * @property string $ip 
 * @property boolean $is_banned 
 * @property \Carbon\Carbon $ban_at
 * @property \Carbon\Carbon $unban_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserIp[] $usersIps 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $users 
 * @method static \Illuminate\Database\Query\Builder|\Ip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereIsBanned($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereBanAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ip whereUnbanAt($value)
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

	public function letters()
	{
		return $this->hasMany('Letter', 'ip_id');
	}

	public function comments()
	{
		return $this->hasMany('Comment', 'ip_id');
	}

	public static function isBanned()
	{
		if(Cache::has('isBannedIp')) {
			return Cache::get('isBannedIp');
		} else {
			$ip = Ip::whereIp(Request::ip())->first(['id', 'ip', 'is_banned']);
			Cache::put('isBannedIp', $ip ? $ip->is_banned : false, 5);
			return $ip ? $ip->is_banned : false;
		}
	}
}
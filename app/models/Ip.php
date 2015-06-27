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
		if(Session::has('user.is_banned_ip')) {
			return Session::get('user.is_banned_ip');
		} else {
			$ip = Ip::whereIp(Request::ip())->first();
			Session::put('user.is_banned_ip', $ip->is_banned);
			return $ip->is_banned;
		}
	}
}
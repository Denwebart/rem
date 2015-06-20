<?php


class UserIp extends Eloquent
{
	protected $table = 'users_ips';

	protected $primaryKey = array('user_id','ip_id');

	public $incrementing = false;

	protected $fillable = [
		'user_id',
		'ip_id'
	];

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function ip()
	{
		return $this->belongsTo('Ip', 'ip_id');
	}
}
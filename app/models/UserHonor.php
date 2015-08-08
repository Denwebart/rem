<?php


class UserHonor extends Eloquent {

	protected $table = 'users_honors';

	protected $fillable = [
		'user_id',
		'honor_id',
		'comment',
	];

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function honor()
	{
		return $this->belongsTo('Honor', 'honor_id');
	}
}
<?php


class UserHonor extends Eloquent {

	protected $table = 'users_honors';

	protected $primaryKey = array('user_id','honor_id');

	public $incrementing = false;

	protected $fillable = [
		'user_id',
		'honor_id',
	];

	public function user()
	{
		return $this->belongsTo('Honor', 'user_id');
	}

	public function honor()
	{
		return $this->belongsTo('Honor', 'honor_id');
	}
}
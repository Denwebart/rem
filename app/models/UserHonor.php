<?php


class UserHonor extends Eloquent {

	protected $table = 'users_honors';

	protected $primaryKey = array('user_id','honor_id');

	public $incrementing = false;

	public function page()
	{
		return $this->belongsTo('Honor', 'honor_id');
	}
}
<?php


class UserPage extends Eloquent {

	protected $table = 'users_pages';

	protected $primaryKey = array('user_id','page_id');

	public $incrementing = false;

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}
}
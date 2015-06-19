<?php

class BanNotification extends \Eloquent
{
	protected $table = 'ban_notifications';

	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'message',
		'ban_at',
		'unban_at',
	];

	public static $rules = [
		'user_id' => 'required|numeric',
		'message' => 'max:500',
	];

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

}
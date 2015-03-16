<?php

class Message extends \Eloquent {

	protected $table = 'messages';

	public static $rules = [
		'user_id_sender' => 'required|numeric',
		'user_id_recipient' => 'required|numeric',
		'message' => 'required',
	];

	protected $fillable = [
		'user_id_sender',
		'user_id_recipient',
		'message',
		'read_at',
	];

	/**
	 * Отправитель
	 */
	public function userSender()
	{
		return $this->belongsTo('User', 'user_id_sender');
	}

	/**
	 * Получатель
	 */
	public function userRecipient()
	{
		return $this->belongsTo('User', 'user_id_recipient');
	}

}
<?php

/**
 * Message
 *
 * @property integer $id 
 * @property integer $user_id_sender 
 * @property integer $user_id_recipient 
 * @property string $message 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $read_at 
 * @property-read \User $userSender 
 * @property-read \User $userRecipient 
 * @method static \Illuminate\Database\Query\Builder|\Message whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Message whereUserIdSender($value)
 * @method static \Illuminate\Database\Query\Builder|\Message whereUserIdRecipient($value)
 * @method static \Illuminate\Database\Query\Builder|\Message whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Message whereReadAt($value)
 */
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
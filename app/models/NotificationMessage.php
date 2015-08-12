<?php

/**
 * NotificationMessage
 *
 * @property integer $id 
 * @property string $message 
 * @property string $desctiption 
 * @method static \Illuminate\Database\Query\Builder|\NotificationMessage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\NotificationMessage whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\NotificationMessage whereDesctiption($value)
 */

class NotificationMessage extends \Eloquent
{
	protected $table = 'notifications_messages';

	public $incrementing = false;

	protected $fillable = [
		'messages',
		'description',
	];

	public static $rules = [
		'messages' => 'required|max:1000',
		'description' => 'required|max:500',
	];


}
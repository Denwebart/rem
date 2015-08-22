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
	public $timestamps = false;

	protected $fillable = [
		'message',
		'description',
	];

	public static $rules = [
		'message' => 'required|max:1000',
		'description' => 'max:500',
	];


}
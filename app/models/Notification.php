<?php

class Notification extends \Eloquent
{
	protected $table = 'notifications';

	const TYPE_POINTS_FOR_COMMENT_ADDED = 1;
	const TYPE_POINTS_FOR_ANSWER_ADDED = 2;
	const TYPE_POINTS_FOR_ARTICLE_ADDED = 3;
	const TYPE_POINTS_FOR_BEST_ANSWER_ADDED = 4;
	const TYPE_POINTS_FOR_COMMENT_REMOVED = 5;
	const TYPE_POINTS_FOR_ANSWER_REMOVED = 6;
	const TYPE_POINTS_FOR_ARTICLE_REMOVED = 7;
	const TYPE_POINTS_FOR_BEST_ANSWER_REMOVED = 8;
	const TYPE_BANNED = 9;
	const TYPE_UNBANNED = 10;
	const TYPE_NEW_COMMENT = 11;
	const TYPE_NEW_ANSWER = 12;
	const TYPE_COMMENT_LIKED = 13;
	const TYPE_COMMENT_DISLIKED = 14;
	const TYPE_BEST_ANSWER = 15;
	const TYPE_RATING = 16;
	const TYPE_SUBSCRIBED_ON_QUESTION = 17;
	const TYPE_SUBSCRIBED_ON_JOURNAL = 18;

	public static $typeIcons = [
		self::TYPE_POINTS_FOR_COMMENT_ADDED => '<i class="material-icons success">attach_money</i>',
		self::TYPE_POINTS_FOR_ANSWER_ADDED => '<i class="material-icons success">attach_money</i>',
		self::TYPE_POINTS_FOR_ARTICLE_ADDED => '<i class="material-icons success">attach_money</i>',
		self::TYPE_POINTS_FOR_BEST_ANSWER_ADDED => '<i class="material-icons success">attach_money</i>',
		self::TYPE_POINTS_FOR_COMMENT_REMOVED => '<i class="material-icons warning">money_off</i>',
		self::TYPE_POINTS_FOR_ANSWER_REMOVED => '<i class="material-icons warning">money_off</i>',
		self::TYPE_POINTS_FOR_ARTICLE_REMOVED => '<i class="material-icons warning">money_off</i>',
		self::TYPE_POINTS_FOR_BEST_ANSWER_REMOVED => '<i class="material-icons warning">money_off</i>',
		self::TYPE_BANNED => '<i class="material-icons danger">lock</i>',
		self::TYPE_UNBANNED => '<i class="material-icons success">lock_open</i>',
		self::TYPE_NEW_COMMENT => '<i class="material-icons info">chat_bubble</i>',
		self::TYPE_NEW_ANSWER => '<i class="material-icons info">question_answer</i>',
		self::TYPE_COMMENT_LIKED => '<i class="material-icons success">thumb_up</i>',
		self::TYPE_COMMENT_DISLIKED => '<i class="material-icons warning">thumb_down</i>',
		self::TYPE_BEST_ANSWER => '<i class="material-icons success">done</i>',
		self::TYPE_RATING => '<i class="material-icons info">star_rate</i>',
		self::TYPE_SUBSCRIBED_ON_QUESTION => '<i class="material-icons info">local_library</i>',
		self::TYPE_SUBSCRIBED_ON_JOURNAL => '<i class="material-icons info">local_library</i>',
	];

	protected $fillable = [
		'user_id',
		'type',
		'message',
	];

	public static $rules = [
		'user_id' => 'required|numeric',
		'type' => 'required|numeric',
		'message' => 'required|max:500',
	];

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function add($userModel, $type)
	{
		self::create([
			'user_id' => $userModel->id,
			'type' => $type,
			'message' => $this->getMessage(),
		]);
	}

}
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
	const TYPE_ANSWER_LIKED = 15;
	const TYPE_ANSWER_DISLIKED = 16;
	const TYPE_BEST_ANSWER = 17;
	const TYPE_RATING = 18;
	const TYPE_SUBSCRIBED_ON_QUESTION = 19;
	const TYPE_SUBSCRIBED_ON_JOURNAL = 20;
	const TYPE_UNSUBSCRIBED_FROM_QUESTION = 21;
	const TYPE_UNSUBSCRIBED_FROM_JOURNAL = 22;
	const TYPE_ROLE_CHANGED = 23;
	const TYPE_COMMENT_DELETED = 24;
	const TYPE_ANSWER_DELETED = 25;
	const TYPE_QUESTION_DELETED = 26;

	public static $typeIcons = [
		self::TYPE_POINTS_FOR_COMMENT_ADDED => '<i class="material-icons mdi-success">attach_money</i>',
		self::TYPE_POINTS_FOR_ANSWER_ADDED => '<i class="material-icons mdi-success">attach_money</i>',
		self::TYPE_POINTS_FOR_ARTICLE_ADDED => '<i class="material-icons mdi-success">attach_money</i>',
		self::TYPE_POINTS_FOR_BEST_ANSWER_ADDED => '<i class="material-icons mdi-success">attach_money</i>',
		self::TYPE_POINTS_FOR_COMMENT_REMOVED => '<i class="material-icons mdi-warning">money_off</i>',
		self::TYPE_POINTS_FOR_ANSWER_REMOVED => '<i class="material-icons mdi-warning">money_off</i>',
		self::TYPE_POINTS_FOR_ARTICLE_REMOVED => '<i class="material-icons mdi-warning">money_off</i>',
		self::TYPE_POINTS_FOR_BEST_ANSWER_REMOVED => '<i class="material-icons mdi-warning">money_off</i>',
		self::TYPE_BANNED => '<i class="material-icons mdi-danger">lock</i>',
		self::TYPE_UNBANNED => '<i class="material-icons mdi-success">lock_open</i>',
		self::TYPE_NEW_COMMENT => '<i class="material-icons mdi-info">chat_bubble</i>',
		self::TYPE_NEW_ANSWER => '<i class="material-icons mdi-info">question_answer</i>',
		self::TYPE_COMMENT_LIKED => '<i class="material-icons mdi-success">thumb_up</i>',
		self::TYPE_COMMENT_DISLIKED => '<i class="material-icons mdi-warning">thumb_down</i>',
		self::TYPE_ANSWER_LIKED => '<i class="material-icons mdi-success">thumb_up</i>',
		self::TYPE_ANSWER_DISLIKED => '<i class="material-icons mdi-warning">thumb_down</i>',
		self::TYPE_BEST_ANSWER => '<i class="material-icons mdi-success">done</i>',
		self::TYPE_RATING => '<i class="material-icons mdi-info">star_rate</i>',
		self::TYPE_SUBSCRIBED_ON_QUESTION => '<i class="material-icons mdi-info">local_library</i>',
		self::TYPE_SUBSCRIBED_ON_JOURNAL => '<i class="material-icons mdi-info">local_library</i>',
		self::TYPE_UNSUBSCRIBED_FROM_QUESTION => '<i class="material-icons mdi-info">local_library</i>',
		self::TYPE_UNSUBSCRIBED_FROM_JOURNAL => '<i class="material-icons mdi-info">local_library</i>',
		self::TYPE_ROLE_CHANGED => '<i class="material-icons mdi-info">perm_identity</i>',
		self::TYPE_COMMENT_DELETED => '<i class="material-icons mdi-danger">delete</i>',
		self::TYPE_ANSWER_DELETED => '<i class="material-icons mdi-danger">delete</i>',
		self::TYPE_QUESTION_DELETED => '<i class="material-icons mdi-danger">delete</i>',
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

	public function add($userModel, $notificationType, $variables = [])
	{
		self::create([
			'user_id' => $userModel->id,
			'type' => $notificationType,
			'message' => $this->getMessage($notificationType, $variables),
		]);
	}

	private function getMessage($notificationType, $variables)
	{
		$notificationMessage = NotificationMessage::find($notificationType);

		if($notificationMessage) {
			return strtr($notificationMessage->message, $variables);
		}
	}

}
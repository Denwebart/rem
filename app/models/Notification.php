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

	public static $notificationSettingColumns = [
		self::TYPE_POINTS_FOR_COMMENT_ADDED => ['notification_points'],
		self::TYPE_POINTS_FOR_ANSWER_ADDED => ['notification_points'],
		self::TYPE_POINTS_FOR_ARTICLE_ADDED => ['notification_points'],
		self::TYPE_POINTS_FOR_BEST_ANSWER_ADDED => ['notification_points'],
		self::TYPE_POINTS_FOR_COMMENT_REMOVED => ['notification_points'],
		self::TYPE_POINTS_FOR_ANSWER_REMOVED => ['notification_points'],
		self::TYPE_POINTS_FOR_ARTICLE_REMOVED => ['notification_points', 'notification_deleted'],
		self::TYPE_POINTS_FOR_BEST_ANSWER_REMOVED => ['notification_points', 'notification_deleted'],
		self::TYPE_BANNED => ['notification_banned'],
		self::TYPE_UNBANNED => ['notification_banned'],
		self::TYPE_NEW_COMMENT => ['notification_new_comments'],
		self::TYPE_NEW_ANSWER => ['notification_new_answers'],
		self::TYPE_COMMENT_LIKED => ['notification_like_dislike'],
		self::TYPE_COMMENT_DISLIKED => ['notification_like_dislike'],
		self::TYPE_ANSWER_LIKED => ['notification_like_dislike'],
		self::TYPE_ANSWER_DISLIKED => ['notification_like_dislike'],
		self::TYPE_BEST_ANSWER => ['notification_best_answer'],
		self::TYPE_RATING => ['notification_rating'],
		self::TYPE_SUBSCRIBED_ON_QUESTION => ['notification_question_subscribed'],
		self::TYPE_SUBSCRIBED_ON_JOURNAL => ['notification_journal_subscribed'],
		self::TYPE_UNSUBSCRIBED_FROM_QUESTION => ['notification_question_subscribed'],
		self::TYPE_UNSUBSCRIBED_FROM_JOURNAL => ['notification_journal_subscribed'],
		self::TYPE_ROLE_CHANGED => ['notification_role_changed'],
		self::TYPE_COMMENT_DELETED => ['notification_deleted', 'notification_points'],
		self::TYPE_ANSWER_DELETED => ['notification_deleted', 'notification_points'],
		self::TYPE_QUESTION_DELETED => ['notification_deleted'],
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
		$notificationMessage = $this->getMessage($notificationType, $variables);

		$settingsColumns = Notification::$notificationSettingColumns[$notificationType];

		if(is_object($userModel->settings)) {
			foreach($settingsColumns as $column) {
				$sendMessage = $userModel->settings->$column;
				if($sendMessage) {
					break;
				}
			}
		} else {
			$sendMessage = true;
		}

		if($sendMessage) {
			$template = EmailTemplate::whereKey('notification')->first();
			$variables = [
				'[siteUrl]' => Config::get('app.url'),
				'[notificationMessage]' => $notificationMessage,
			];
			$content = strtr($template->html, $variables);

			Mail::queue('layouts.email', ['content' => $content, 'userModel' => $userModel], function($message) use ($userModel, $template)
			{
				$siteEmail = ($siteEmailModel = Setting::whereKey('siteEmail')->whereIsActive(1)->first())
					? $siteEmailModel->value
					: Config::get('settings.adminEmail');
				$message->from($siteEmail, Config::get('settings.adminName'));
				$message->to($userModel->email, $userModel->login)
					->subject($template->subject);
			});
			Log::info("Email with notification for [{$userModel->login}] successfully sent. Notfication: [{$notificationMessage}]");
		}

		Notification::create([
			'user_id' => $userModel->id,
			'type' => $notificationType,
			'message' => $notificationMessage,
		]);
	}

	private function getMessage($notificationType, $variables)
	{
		$notificationMessage = NotificationMessage::select('id', 'message')->whereId($notificationType)->first();
		if($notificationMessage) {
			return strtr($notificationMessage->message, $variables);
		}
	}
}
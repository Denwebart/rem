<?php

class UserSetting extends \Eloquent
{
	protected $table = 'users_settings';

	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'notification_deleted',
		'notification_points',
		'notification_new_comments',
		'notification_new_answers',
		'notification_like_dislike',
		'notification_best_answer',
		'notification_rating',
		'notification_journal_subscribed',
		'notification_question_subscribed',
		'notification_banned',
		'notification_role_changed',
		/* for only admin */
		'notification_all_new_user',
		'notification_all_new_question',
		'notification_all_new_article',
		'notification_all_new_answer',
		'notification_all_new_comment',
	];

	/**
	 * Пользователь
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

}
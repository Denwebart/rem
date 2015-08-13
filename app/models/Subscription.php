<?php

/**
 * Subscription
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property integer $page_id 
 * @property integer $journal_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \User $user 
 * @property-read \Page $page 
 * @property-read \User $userJournal 
 * @property-read \Illuminate\Database\Eloquent\Collection|\SubscriptionNotification[] $notifications 
 * @method static \Illuminate\Database\Query\Builder|\Subscription whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Subscription whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Subscription wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Subscription whereJournalId($value)
 * @method static \Illuminate\Database\Query\Builder|\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Subscription whereUpdatedAt($value)
 */
class Subscription extends Eloquent {

	protected $table = 'subscriptions';

	const FIELD_PAGE_ID = 'page_id';
	const FIELD_JOURNAL_ID = 'journal_id';

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}

	public function userJournal()
	{
		return $this->belongsTo('User', 'journal_id');
	}

	public function notifications()
	{
		return $this->hasMany('SubscriptionNotification', 'subscription_id')->orderBy('created_at', 'DESC');
	}

	public static function boot()
	{
		parent::boot();

		static::deleted(function($subscription)
		{
			$subscription->notifications()->delete();
		});
	}

	public function onJournal()
	{
		return (is_null($this->page_id) && !is_null($this->journal_id)) ? true : false;
	}

	public function onPage()
	{
		return (!is_null($this->page_id) && is_null($this->journal_id)) ? true : false;
	}
}
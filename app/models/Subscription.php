<?php

class Subscription extends Eloquent {

	protected $table = 'subscriptions';

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}

	public function notifications()
	{
		return $this->hasMany('SubscriptionNotification', 'subscription_id');
	}
}
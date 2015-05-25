<?php

class SubscriptionNotification extends \Eloquent
{
	protected $table = 'subscriptions_notifications';

	protected $fillable = [
		'subscription_id',
		'message',
	];

	public static $rules = [
		'subscription_id' => 'required|numeric',
		'message' => 'required|max:500',
	];

	public function subscription()
	{
		return $this->belongsTo('Subscription', 'subscription_id');
	}

	public function page()
	{
		return $this->belongsToMany('Page', 'subscriptions');
	}

	public function user()
	{
		return $this->belongsToMany('User', 'subscriptions');
	}

}
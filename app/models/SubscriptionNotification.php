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

	public static function addNotification($pageModel, $message)
	{
		$users = User::whereHas('subscriptions', function($query) use ($pageModel) {
				$query->wherePageId($pageModel->id);
			})
			->where('id', '!=', Auth::user()->id)
			->get();
		if(count($users)) {
			$data = [];
			foreach($users as $user) {
				$data[] = [
					'subscription_id' => $user->subscriptions()->wherePageId($pageModel->id)->first()->id,
					'message' => $message,
					'created_at'=> date('Y-m-d H:i:s'),
				];
			}
			SubscriptionNotification::insert($data);
		}
	}

}
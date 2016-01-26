<?php

class HeaderWidget
{
	public $newQuestions;
	public $newArticles;
	public $newComments;
	public $newAnswers;
	public $notPublishedAnswers;
	public $notPublishedComments;
	public $newLetters;
	public $deletedLetters;
	public $newUsers;
	public $newMessages;
    public $newNotifications;
	public $newSubscriptionsNotifications;
	public $isBannedIp;

	public function __construct()
	{
		if(Auth::user()->isAdmin() || Auth::user()->isModerator()) {
			$this->newQuestions = $this->getFromCache('newQuestions');
			$this->newArticles = $this->getFromCache('newArticles');
			$this->newComments = $this->getFromCache('newComments');
			$this->newAnswers = $this->getFromCache('newAnswers');
			$this->notPublishedAnswers = $this->getFromCache('notPublishedAnswers');
			$this->notPublishedComments = $this->getFromCache('notPublishedComments');
		}
		if(Auth::user()->isAdmin()) {
			$this->newLetters = $this->getFromCache('newLetters');
			$this->deletedLetters = $this->getFromCache('deletedLetters');
			$this->newUsers = $this->getFromCache('newUsers');
		}
		$this->newMessages = $this->getFromCacheForUser('newMessages');
		$this->newNotifications = $this->getFromCacheForUser('newNotifications');
		$this->newSubscriptionsNotifications = $this->getFromCacheForUser('newSubscriptionsNotifications');

		if(Cache::has('headerWidget.isBannedIp.' . Request::ip())) {
			$this->isBannedIp = Cache::get('headerWidget.isBannedIp.' . Request::ip());
		} else {
			$this->isBannedIp = Ip::isBanned();
			Cache::put('headerWidget.isBannedIp.' . Request::ip(), $this->isBannedIp, 60 * 24);
		}
	}

	public function show($page = null)
	{
		if(Auth::user()->isAdmin()) {
			$letters = $this->newLetters;
			$letters = (string) View::make('widgets.header.letters', compact('letters'))->with('limit', 5);
		} else {
			$letters = [];
		}

		$messages = $this->newMessages;
		$messages = (string) View::make('widgets.header.messages', compact('messages'))->with('limit', 5);

		$notifications = $this->newNotifications;
		$notifications = (string) View::make('widgets.header.notifications', compact('notifications'))->with('limit', 5);

		return (string) View::make('widgets.header.index', compact('letters', 'messages', 'notifications', 'page'))->with('user', Auth::user())->render();
	}


	public function newLetters() {
		return Letter::select('id', 'user_id', 'user_name', 'user_email', 'subject', 'created_at')
			->whereNull('read_at')
			->whereNull('deleted_at')
			->with([
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'email', 'firstname', 'lastname', 'is_online', 'last_activity');
				}
			])
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function newMessages() {
		return Message::select('id', 'user_id_sender', 'user_id_recipient', 'message', 'created_at')
			->whereUserIdRecipient(Auth::user()->id)
			->whereNull('read_at')
			->with([
				'userSender' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'email', 'firstname', 'lastname', 'is_online', 'last_activity');
				}
			])
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function newNotifications() {
		return Notification::select('id', 'user_id', 'type', 'message', 'created_at')
			->whereUserId(Auth::user()->id)
			->with([
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'email', 'firstname', 'lastname', 'is_online', 'last_activity');
				}
			])
			->orderBy('created_at', 'DESC')
			->orderBy('id', 'DESC')
			->get();
	}

	public function newSubscriptionsNotifications() {
		return SubscriptionNotification::select('id', 'subscription_id', 'message', 'created_at')
			->whereHas('subscription', function($query){
				$query->whereUserId(Auth::user()->id);
			})
			->with('subscription')
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function newUsers() {
		return User::select('id', 'created_at')
			->where('created_at', '>', $this->getLastActivity())
			->get();
	}

	public function deletedLetters() {
		return Letter::whereNotNull('deleted_at')
			->orderBy('deleted_at', 'DESC')
			->get();
	}

	public function newQuestions() {
		return Page::select('id', 'type', 'user_id', 'created_at')
			->whereType(Page::TYPE_QUESTION)
			->where('user_id', '!=', Auth::user()->id)
			->where('created_at', '>', $this->getLastActivity())
			->get();
	}

	public function newArticles() {
		return Page::select('id', 'type', 'user_id', 'created_at')
			->whereType(Page::TYPE_ARTICLE)
			->where('user_id', '!=', Auth::user()->id)
			->where('created_at', '>', $this->getLastActivity())
			->get();
	}

	public function newComments() {
		return Comment::select('id', 'is_answer', 'user_id', 'created_at')
			->whereIsAnswer(0)
			->where('created_at', '>', $this->getLastActivity())
			->where('user_id', '!=', Auth::user()->id)
			->get();
	}

	public function newAnswers() {
		return Comment::select('id', 'is_answer', 'user_id', 'created_at')
			->whereIsAnswer(1)
			->where('created_at', '>', $this->getLastActivity())
			->where('user_id', '!=', Auth::user()->id)
			->get();
	}

	public function notPublishedComments() {
		return Comment::select('id', 'is_answer', 'user_id', 'is_published', 'created_at')
			->whereIsAnswer(0)
			->whereIsPublished(0)
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function notPublishedAnswers() {
		return Comment::select('id', 'is_answer', 'user_id', 'is_published', 'created_at')
			->whereIsAnswer(1)
			->whereIsPublished(0)
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function getLastActivity()
	{
		return Session::has('user.lastActivity')
			? Session::get('user.lastActivity')
			: Auth::user()->last_activity;
	}

	protected function getFromCache($key)
	{
		$cache = Cache::has('headerWidget.' . $key) ? Cache::get('headerWidget.' . $key) : [];
		if(isset($cache[Auth::user()->id])) {
			return  $cache[Auth::user()->id];
		} else {
			$result = $this->$key();
			$cache[Auth::user()->id] = $result;
			Cache::put('headerWidget.' . $key, $cache, 60);
			return $result;
		}
	}

	protected function getFromCacheForUser($key)
	{
		if(Cache::has('headerWidget.' . $key . '.' . Auth::user()->id)) {
			return Cache::get('headerWidget.' . $key . '.' . Auth::user()->id);
		} else {
			$result = $this->$key();
			Cache::put('headerWidget.' . $key . '.' . Auth::user()->id, $result, 60 * 24);
			return $result;
		}
	}
}
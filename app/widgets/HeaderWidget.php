<?php

class HeaderWidget
{
	public $newLetters = [];
	public $deletedLetters;
	public $newMessages;
	public $newNotifications;
	public $newSubscriptionsNotifications;
	public $newUsers;
	public $newQuestions;
	public $newArticles;
	public $newComments;
	public $newAnswers;
	public $notPublishedAnswers;
	public $notPublishedComments;
	public $isBannedIp;

	public function __construct()
	{
		if(Auth::user()->isAdmin() || Auth::user()->isModerator()) {
			if(Cache::has('headerWidget.newQuestions.' . Auth::user()->id)) {
				$this->newQuestions = Cache::get('headerWidget.newQuestions.' . Auth::user()->id);
			} else {
				$this->newQuestions = $this->newQuestions();
				Cache::put('headerWidget.newQuestions.' . Auth::user()->id, $this->newQuestions, 60);
			}
			if(Cache::has('headerWidget.newArticles.' . Auth::user()->id)) {
				$this->newArticles = Cache::get('headerWidget.newArticles.' . Auth::user()->id);
			} else {
				$this->newArticles = $this->newArticles();
				Cache::put('headerWidget.newArticles.' . Auth::user()->id, $this->newArticles, 60);
			}
			if(Cache::has('headerWidget.newComments.' . Auth::user()->id)) {
				$this->newComments = Cache::get('headerWidget.newComments.' . Auth::user()->id);
			} else {
				$this->newComments = $this->newComments();
				Cache::put('headerWidget.newComments.' . Auth::user()->id, $this->newComments, 60);
			}
			if(Cache::has('headerWidget.newAnswers.' . Auth::user()->id)) {
				$this->newAnswers = Cache::get('headerWidget.newAnswers.' . Auth::user()->id);
			} else {
				$this->newAnswers = $this->newAnswers();
				Cache::put('headerWidget.newAnswers.' . Auth::user()->id, $this->newAnswers, 60);
			}
			if(Cache::has('headerWidget.newUsers.' . Auth::user()->id)) {
				$this->newUsers = Cache::get('headerWidget.newUsers.' . Auth::user()->id);
			} else {
				$this->newUsers = $this->newUsers();
				Cache::put('headerWidget.newUsers.' . Auth::user()->id, $this->newUsers, 60);
			}
			if(Cache::has('headerWidget.notPublishedAnswers.' . Auth::user()->id)) {
				$this->notPublishedAnswers = Cache::get('headerWidget.notPublishedAnswers.' . Auth::user()->id);
			} else {
				$this->notPublishedAnswers = $this->notPublishedAnswers();
				Cache::put('headerWidget.notPublishedAnswers.' . Auth::user()->id, $this->notPublishedAnswers, 60);
			}
			if(Cache::has('headerWidget.notPublishedComments.' . Auth::user()->id)) {
				$this->notPublishedComments = Cache::get('headerWidget.notPublishedComments.' . Auth::user()->id);
			} else {
				$this->notPublishedComments = $this->notPublishedComments();
				Cache::put('headerWidget.notPublishedComments.' . Auth::user()->id, $this->notPublishedComments, 60);
			}
		}
		if(Auth::user()->isAdmin()) {
			if(Cache::has('headerWidget.newLetters.' . Auth::user()->id)) {
				$this->newLetters = Cache::get('headerWidget.newLetters.' . Auth::user()->id);
			} else {
				$this->newLetters = $this->newLetters();
				Cache::put('headerWidget.newLetters.' . Auth::user()->id, $this->newLetters, 60);
			}
		}
		if(Cache::has('headerWidget.newMessages.' . Auth::user()->id)) {
			$this->newMessages = Cache::get('headerWidget.newMessages.' . Auth::user()->id);
		} else {
			$this->newMessages = $this->newMessages();
			Cache::put('headerWidget.newMessages.' . Auth::user()->id, $this->newMessages, 60);
		}
		if(Cache::has('headerWidget.newNotifications.' . Auth::user()->id)) {
			$this->newNotifications = Cache::get('headerWidget.newNotifications.' . Auth::user()->id);
		} else {
			$this->newNotifications = $this->newNotifications();
			Cache::put('headerWidget.newNotifications.' . Auth::user()->id, $this->newNotifications, 60);
		}
		if(Cache::has('headerWidget.newSubscriptionsNotifications.' . Auth::user()->id)) {
			$this->newSubscriptionsNotifications = Cache::get('headerWidget.newSubscriptionsNotifications.' . Auth::user()->id);
		} else {
			$this->newSubscriptionsNotifications = $this->newSubscriptionsNotifications();
			Cache::put('headerWidget.newSubscriptionsNotifications.' . Auth::user()->id, $this->newSubscriptionsNotifications, 60);
		}
		if(Cache::has('headerWidget.isBannedIp.' . Auth::user()->id)) {
			$this->isBannedIp = Cache::get('headerWidget.isBannedIp.' . Auth::user()->id);
		} else {
			$this->isBannedIp = Ip::isBanned();
			Cache::put('headerWidget.isBannedIp.' . Auth::user()->id, $this->isBannedIp, 60);
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
		$letters = Letter::whereNotNull('deleted_at')
			->orderBy('deleted_at', 'DESC')
			->get();

		$this->deletedLetters = count($letters);

		return $letters;
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
}
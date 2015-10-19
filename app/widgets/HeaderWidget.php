<?php

class HeaderWidget
{
	public $newLetters;
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
			$this->newQuestions = $this->newQuestions();
			$this->newArticles = $this->newArticles();
			$this->newComments = $this->newComments();
			$this->newAnswers = $this->newAnswers();
			$this->newUsers = $this->newUsers();
			$this->notPublishedAnswers = $this->notPublishedAnswers();
			$this->notPublishedComments = $this->notPublishedComments();
		}
		if(Auth::user()->isAdmin()) {
			$this->newLetters = $this->newLetters();
		}
		$this->newMessages = $this->newMessages();
		$this->newNotifications = $this->newNotifications();
		$this->newSubscriptionsNotifications = $this->newSubscriptionsNotifications();
		$this->isBannedIp = Ip::isBanned();
	}

	public function show($page = null)
	{
		if(Auth::user()->isAdmin()) {
			$letters = $this->newLetters;
			$letters = (string) View::make('widgets.header.letters', compact('letters'));
		} else {
			$letters = '';
		}
		$messages = $this->newMessages;
		$messages = (string) View::make('widgets.header.messages', compact('messages'));

		$notifications = $this->newNotifications;
		$notifications = (string) View::make('widgets.header.notifications', compact('notifications'));

		return (string) View::make('widgets.header.index', compact('letters', 'messages', 'notifications', 'page'))->with('user', Auth::user())->render();
	}


	public function newLetters($limit = 5) {
		return Letter::select('id', 'user_id', 'user_name', 'user_email', 'subject', 'created_at')
			->whereNull('read_at')
			->whereNull('deleted_at')
			->with([
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'email', 'firstname', 'lastname', 'is_online', 'last_activity');
				}
			])
			->orderBy('created_at', 'DESC')
			->paginate($limit);
	}

	public function newMessages($limit = 5) {
		return Message::select('id', 'user_id_sender', 'user_id_recipient', 'message', 'created_at')
			->whereUserIdRecipient(Auth::user()->id)
			->whereNull('read_at')
			->with([
				'userSender' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'email', 'firstname', 'lastname', 'is_online', 'last_activity');
				}
			])
			->orderBy('created_at', 'DESC')
			->paginate($limit);
	}

	public function newNotifications($limit = 5) {
		return Notification::select('id', 'user_id', 'type', 'message', 'created_at')
			->whereUserId(Auth::user()->id)
			->with([
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'email', 'firstname', 'lastname', 'is_online', 'last_activity');
				}
			])
			->orderBy('created_at', 'DESC')
			->orderBy('id', 'DESC')
			->paginate($limit);
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
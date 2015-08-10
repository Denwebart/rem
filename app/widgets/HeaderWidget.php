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
	public $isBannedIp;

	public function __construct()
	{
		if(Auth::user()->isAdmin() || Auth::user()->isModerator()) {
			$this->newQuestions = $this->newQuestions();
			$this->newArticles = $this->newArticles();
			$this->newComments = $this->newComments();
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
		return Letter::whereNull('read_at')
			->whereNull('deleted_at')
			->with('user')
			->orderBy('created_at', 'DESC')
			->paginate($limit);
	}

	public function newMessages($limit = 5) {
		return Message::whereUserIdRecipient(Auth::user()->id)
			->whereNull('read_at')
			->with('userSender')
			->orderBy('created_at', 'DESC')
			->paginate($limit);
	}

	public function newNotifications($limit = 20) {
		return Notification::whereUserId(Auth::user()->id)
			->with('user')
			->orderBy('created_at', 'DESC')
			->orderBy('id', 'DESC')
			->paginate($limit);
	}

	public function newSubscriptionsNotifications() {
		return SubscriptionNotification::whereHas('subscription', function($query){
				$query->whereUserId(Auth::user()->id);
			})
			->with('subscription')
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function newUsers() {
		$users = User::all();

		$this->newUsers = count($users);

		return $users;
	}

	public function deletedLetters() {
		$letters = Letter::whereNotNull('deleted_at')
			->orderBy('deleted_at', 'DESC')
			->get();

		$this->deletedLetters = count($letters);

		return $letters;
	}

	public function newQuestions() {
		return Page::whereType(Page::TYPE_QUESTION)
			->whereNull('published_at')
			->whereIsPublished(0)
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function newArticles() {
		return Page::whereType(Page::TYPE_ARTICLE)
			->whereNull('published_at')
			->whereIsPublished(0)
			->orderBy('created_at', 'DESC')
			->get();
	}

	public function newComments() {
		return Comment::whereIsPublished(0)
			->orderBy('created_at', 'DESC')
			->get();
	}

}
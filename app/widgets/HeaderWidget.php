<?php

class HeaderWidget
{
	public $newLetters;
	public $deletedLetters;
	public $newMessages;
	public $newUsers;

	public function __construct()
	{
		if(Auth::user()->isAdmin()) {
			$this->newLetters = $this->newLetters();
		}
		$this->newMessages = $this->newMessages();
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

		return (string) View::make('widgets.header.index', compact('letters', 'messages', 'page'))->with('user', Auth::user())->render();
	}


	public function newLetters() {
		return Letter::whereNull('read_at')
				->whereNull('deleted_at')
				->orderBy('created_at', 'DESC')
				->get();
	}

	public function newMessages() {
		return Message::whereUserIdRecipient(Auth::user()->id)
			->whereNull('read_at')
			->with('userSender')
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
}
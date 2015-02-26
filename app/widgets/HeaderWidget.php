<?php

class HeaderWidget
{
	public $newLetters;
	public $deletedLetters;
	public $newMessages;
	public $newUsers;

	public function show()
	{
		$letters = $this->newLetters();
		$letters = (string) View::make('widgets.header.letters', compact('letters'));

		$messages = $this->newMessages();
		$messages = (string) View::make('widgets.header.messages', compact('messages'));

		return (string) View::make('widgets.header.index', compact('letters', 'messages'))->render();
	}


	public function newLetters() {
		$letters = Letter::whereNull('read_at')
			->orderBy('created_at', 'DESC')
			->get();

		$this->newLetters = count($letters);

		return $letters;
	}

	public function newMessages() {
		$messages = Message::whereUserIdRecipient(Auth::user()->id)
			->orderBy('created_at', 'DESC')
			->get();

		$this->newMessages = count($messages);

		return $messages;
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
<?php

class HeaderWidget
{
	public $newLetters;
	public $deletedLetters;

	public function show()
	{
		$letters = $this->newLetters();
		$letters = (string) View::make('widgets.header.letters', compact('letters'));

		return (string) View::make('widgets.header.index', compact('letters'))->render();
	}


	public function newLetters() {
		$letters = Letter::whereNull('read_at')
			->orderBy('created_at', 'DESC')
			->get();

		$this->newLetters = count($letters);

		return $letters;
	}

	public function deletedLetters() {
		$letters = Letter::whereNotNull('deleted_at')
			->orderBy('deleted_at', 'DESC')
			->get();

		$this->deletedLetters = count($letters);

		return $letters;
	}
}
<?php

class HeaderWidget
{
	public $newLetters;

	public function show()
	{
		$letters = $this->letters();

		return (string) View::make('widgets.header.index', compact('letters'))->render();
	}

	public function letters()
	{
		$letters = Letter::whereNull('read_at')
			->orderBy('created_at', 'DESC')
			->get();

		$this->newLetters = count($letters);

		return (string) View::make('widgets.header.letters', compact('letters'));
	}
}
<?php

class AdminWidget
{
	public $newLetters;

	public function letters()
	{
		$letters = Letter::whereNull('read_at')
			->orderBy('created_at', 'DESC')
			->get();

		$this->newLetters = count($letters);

		return (string) View::make('widgets.admin.letters', compact('letters'))->render();
	}

}
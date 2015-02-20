<?php

class AdminWidget
{
	public function letters()
	{
		$letters = Letter::whereNull('read_at')
			->orderBy('created_at', 'DESC')
			->get();

		return (string) View::make('widgets.admin.letters', compact('letters'))->render();
	}

}
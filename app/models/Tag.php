<?php

class Tag extends \Eloquent
{
	protected $table = 'tags';

	protected $fillable = [
		'image',
		'title',
	];

	public static $rules = [
		'image' => 'mimes:jpeg,bmp,png|max:1024',
		'title' => 'max:100',
	];

	public function pages()
	{
		return $this->belongsToMany('Page', 'pages_tags');
	}
}
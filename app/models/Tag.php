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

	public static function boot()
	{
		parent::boot();

		static::deleted(function($tag)
		{
			$tag->pagesTags()->delete();
		});
	}

	public function pages()
	{
		return $this->belongsToMany('Page', 'pages_tags');
	}

	public function pagesTags()
	{
		return $this->hasMany('PageTag', 'tag_id');
	}
}
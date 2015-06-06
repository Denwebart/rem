<?php

class Tag extends \Eloquent
{
	protected $table = 'tags';

	public $timestamps = false;

	protected $fillable = [
		'image',
		'title',
	];

	public static $rules = [
		'image' => 'mimes:jpeg,bmp,png|max:1024',
		'title' => 'required|max:100',
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

	public function getImage($prefix = null, $options = [])
	{
		if(isset($options['class'])) {
			$options['class'] = ($this->image) ? 'img-responsive ' . $options['class'] : 'img-responsive image-default ' . $options['class'];
		} else {
			$options['class'] = ($this->image) ? 'img-responsive' : 'img-responsive image-default';
		}
		$prefix = is_null($prefix) ? '' : ($prefix . '_');
		if($this->image){
			return HTML::image('/uploads/' . $this->getTable() . '/' . $prefix . $this->image, $this->title, $options);
		} else {
			return HTML::image(Config::get('settings.' . $prefix . 'defaultTagImage'), $this->title, $options);
		}
	}
}
<?php

class Honor extends \Eloquent
{
	protected $table = 'honors';

	public $timestamps = false;

	protected $fillable = [
		'title',
		'image',
		'description',
	];

	public static $rules = [
		'title' => 'required|max:100',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'description' => 'max:500',
	];

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
			return HTML::image(Config::get('settings.' . $prefix . 'defaultHonorImage'), $this->title, $options);
		}
	}
}
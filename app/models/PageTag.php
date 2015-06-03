<?php


class PageTag extends Eloquent {

	protected $table = 'pages_tags';

	protected $primaryKey = ['page_id','tag_id'];

	public $incrementing = false;
	public $timestamps = false;

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}

	public function user()
	{
		return $this->belongsTo('Tag', 'tag_id');
	}
}
<?php

class Rule extends \Eloquent
{
	protected $table = 'rules';

	protected $fillable = [
		'position',
		'is_published',
		'title',
		'description',
	];

	public static $rules = [
		'position' => 'required|integer|unique:rules',
		'is_published' => 'boolean',
		'title' => 'required|max:500',
		'description' => 'required|max:2000',
	];

}
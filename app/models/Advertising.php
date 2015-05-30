<?php

class Advertising extends \Eloquent
{
	protected $table = 'advertising';

//	const TYPE_BOOLEAN = 1;
//	const TYPE_INTEGER = 2;
//	const TYPE_TEXT = 3;
//	const TYPE_HTML = 4;
//
//	public static $types = array(
//		self::TYPE_BOOLEAN => 'Логическое значение',
//		self::TYPE_INTEGER => 'Целое число',
//		self::TYPE_TEXT => 'Короткий текст',
//		self::TYPE_HTML => 'HTML-код',
//	);

	protected $fillable = [
		'title',
		'position',
		'text',
		'is_active',
	];

	public static $rules = [
		'title' => 'max:100',
		'position' => 'numeric',
		'description' => 'required',
		'is_active' => 'numeric',
	];
}
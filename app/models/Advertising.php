<?php

class Advertising extends \Eloquent
{
	protected $table = 'advertising';

	const AREA_LEFT_SIDEBAR = 1;
	const AREA_RIGHT_SIDEBAR = 2;
	const AREA_CONTENT_TOP = 3;
	const AREA_CONTENT_MIDDLE= 4;
	const AREA_CONTENT_BOTTOM = 5;
	const AREA_SITE_BOTTOM = 6;

	public static $types = [
		self::AREA_LEFT_SIDEBAR => 'В левой колонке',
		self::AREA_RIGHT_SIDEBAR => 'В правой колонке',
		self::AREA_CONTENT_TOP => 'Над текстом страницы',
		self::AREA_CONTENT_MIDDLE => 'Под текстом страницы',
		self::AREA_CONTENT_BOTTOM => 'В самом низу страницы',
		self::AREA_SITE_BOTTOM => 'Внизу сайта',
	];

	protected $fillable = [
		'area',
		'position',
		'access',
		'title',
		'is_show_title',
		'code',
		'description',
		'is_active',
	];

	public static $rules = [
		'area' => 'required',
		'position' => 'numeric',
		'access' => 'numeric',
		'title' => 'max:100',
		'is_show_title' => 'boolean',
		'code' => 'required',
		'description' => 'max:1000',
		'is_active' => 'boolean',
	];
}
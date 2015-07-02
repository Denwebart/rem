<?php

/**
 * Advertising
 *
 * @property integer $id 
 * @property boolean $area 
 * @property integer $position 
 * @property string $title 
 * @property boolean $is_show_title 
 * @property boolean $access 
 * @property string $code 
 * @property string $description 
 * @property boolean $is_active 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereArea($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereIsShowTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereAccess($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Advertising whereUpdatedAt($value)
 */

class Advertising extends \Eloquent
{
	protected $table = 'advertising';

	const AREA_LEFT_SIDEBAR = 1;
	const AREA_RIGHT_SIDEBAR = 2;
	const AREA_CONTENT_TOP = 3;
	const AREA_CONTENT_MIDDLE= 4;
	const AREA_CONTENT_BOTTOM = 5;
	const AREA_SITE_BOTTOM = 6;

	public static $areas = [
		self::AREA_LEFT_SIDEBAR => 'В левой колонке',
		self::AREA_RIGHT_SIDEBAR => 'В правой колонке',
		self::AREA_CONTENT_TOP => 'Над текстом страницы',
		self::AREA_CONTENT_MIDDLE => 'Под текстом страницы',
		self::AREA_CONTENT_BOTTOM => 'В самом низу страницы',
		self::AREA_SITE_BOTTOM => 'Внизу сайта',
	];

	const ACCESS_FOR_ALL = 1;
	const ACCESS_FOR_REGISTERED = 2;
	const ACCESS_FOR_GUEST = 3;

	public static $access = [
		self::ACCESS_FOR_ALL => 'Для всех',
		self::ACCESS_FOR_REGISTERED => 'Для зарегистрированных',
		self::ACCESS_FOR_GUEST => 'Для незарегистрированных',
	];

	const TYPE_ADVERTISING = 1;
	const TYPE_WIDGET = 2;

	public static $types = [
		self::TYPE_ADVERTISING => 'Реклама',
		self::TYPE_WIDGET => 'Виджет',
	];

	const WIDGET_LATEST = 1;
	const WIDGET_BEST = 2;
	const WIDGET_POPULAR = 3;
	const WIDGET_UNPOPULAR = 4;
	const WIDGET_COMMENTS = 5;
	const WIDGET_QUESTIONS = 6;

	public static $widgets = [
		self::WIDGET_LATEST => 'Самое новое',
		self::WIDGET_BEST => 'По голосам',
		self::WIDGET_POPULAR => 'Самое популярное',
		self::WIDGET_UNPOPULAR => 'Аутсайдеры',
		self::WIDGET_COMMENTS => 'Комментарии',
		self::WIDGET_QUESTIONS => 'Новые вопросы',
	];

	protected $fillable = [
		'type',
		'area',
		'position',
		'access',
		'title',
		'is_show_title',
		'code',
		'limit',
		'description',
		'is_active',
	];

	public static $rules = [
		'type' => 'required|numeric',
		'area' => 'required',
		'position' => 'numeric',
		'access' => 'numeric',
		'title' => 'max:100',
		'is_show_title' => 'boolean',
		'code' => 'required',
		'limit' => 'numeric|min:1|max:100',
		'description' => 'max:1000',
		'is_active' => 'boolean',
	];

	public function pagesTypes()
	{
		return $this->hasMany('AdvertisingPage', 'advertising_id');
	}
}
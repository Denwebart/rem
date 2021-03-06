<?php

/**
 * Setting
 *
 * @property integer $id
 * @property string $key
 * @property string $category
 * @property boolean $type
 * @property string $title
 * @property string $description
 * @property string $value
 * @property boolean $isActive
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Setting whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereKey($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\Setting whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Setting whereTitle($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereDescription($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereValue($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereIsActive($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereUpdatedAt($value) 
 */

class Setting extends \Eloquent
{
	protected $table = 'settings';

	const TYPE_BOOLEAN = 1;
	const TYPE_INTEGER = 2;
	const TYPE_TEXT = 3;
	const TYPE_HTML = 4;

	public static $types = [
		self::TYPE_BOOLEAN => 'Логическое значение',
		self::TYPE_INTEGER => 'Целое число',
		self::TYPE_TEXT => 'Короткий текст',
		self::TYPE_HTML => 'HTML-код',
	];

	const THEME_VALUE_DEFAULT = '';
	const THEME_VALUE_NEW_YEAR = 'new-year';
	const THEME_VALUE_HALLOWEEN = 'halloween';
	const THEME_VALUE_8_MARCH = '8-march';

	public static $themeValues = [
		self::THEME_VALUE_DEFAULT => 'Обычная',
		self::THEME_VALUE_NEW_YEAR => 'Новогодняя',
		self::THEME_VALUE_HALLOWEEN => 'Halloween',
		self::THEME_VALUE_8_MARCH => '8 марта',
	];

	protected $fillable = [
		'key',
		'category',
		'type',
		'title',
		'description',
		'value',
		'is_active',
	];

	public static $rules = [
		'key' => 'max:100',
		'category' => 'max:100',
		'type' => 'numeric',
		'title' => 'max:100',
		'description' => 'max:500',
		'value' => 'required',
		'is_active' => 'required',
	];

	public static function boot()
	{
		parent::boot();

		static::saved(function($setting)
		{
			Cache::forget('settings.' . $setting->category);
		});
	}

	/**
	 * Получение настроек для сайта
	 *
	 */
	public static function getSettings($category = [])
	{
		$settings = Setting::whereIn('category', $category)
			->whereIsActive(1)
			->get(['id', 'key', 'category', 'value', 'is_active'])
			->toArray();
		$result = [];
		foreach($settings as $setting) {
			$result[$setting['key']] = $setting;
		}

		return $result;
	}
}
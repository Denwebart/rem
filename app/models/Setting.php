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

	public static $types = array(
		self::TYPE_BOOLEAN => 'Логическое значение',
		self::TYPE_INTEGER => 'Целое число',
		self::TYPE_TEXT => 'Короткий текст',
		self::TYPE_HTML => 'HTML-код',
	);

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
		'value' => 'required|max:500',
		'is_active' => 'required',
	];

	/**
	 * Получение настроек для сайта
	 *
	 */
	public static function getSettings()
	{
		$settings = Setting::whereCategory('Site')
			->whereIsActive(1)
			->get()
			->toArray();
		$result = [];
		foreach($settings as $setting) {
			$result[$setting['key']] = $setting;
		}

		return $result;
	}
}
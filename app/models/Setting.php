<?php

/**
 * Setting
 *
 * @property integer $id
 * @property string $key
 * @property boolean $type
 * @property string $title
 * @property string $description
 * @property string $value
 * @property boolean $isActive
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Setting whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Setting whereKey($value) 
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

	protected $fillable = [
		'key',
		'type',
		'title',
		'description',
		'value',
		'is_active',
	];
}
<?php

/**
 * EmailTemplate
 *
 * @property integer $id 
 * @property string $key 
 * @property string $subject 
 * @property string $html 
 * @property string $description 
 * @method static \Illuminate\Database\Query\Builder|\EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\EmailTemplate whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Query\Builder|\EmailTemplate whereHtml($value)
 * @method static \Illuminate\Database\Query\Builder|\EmailTemplate whereDescription($value)
 */

class EmailTemplate extends \Eloquent {

	protected $table = 'email_templates';

	public $timestamps = false;

	protected $fillable = [
		'subject',
		'html',
		'description',
	];

	public static $rules = [
		'subject' => 'required|max:500',
		'html' => 'required',
		'description' => 'max:500',
	];

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->key . '/';
	}
}
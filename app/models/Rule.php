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
		'position' => 'required|integer|unique:rules,position,:id',
		'is_published' => 'boolean',
		'title' => 'required|max:500',
		'description' => 'required|max:2000',
	];

	public static function rules($id = false, $merge = [])
	{
		$rules = SELF::$rules;
		if ($id) {
			foreach ($rules as &$rule) {
				$rule = str_replace(':id', $id, $rule);
			}
		}
		return array_merge( $rules, $merge );
	}

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/editor/';
	}
}
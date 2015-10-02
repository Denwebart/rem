<?php

/**
 * Rule
 *
 * @property integer $id 
 * @property integer $position 
 * @property boolean $is_published 
 * @property string $title 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Rule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Rule wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Rule whereIsPublished($value)
 * @method static \Illuminate\Database\Query\Builder|\Rule whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Rule whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Rule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Rule whereUpdatedAt($value)
 */
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
     * Перемещение изображений из временной папки
     *
     * @param $tempPath
     * @return mixed
     */
    public function saveEditorImages($tempPath)
    {
        $moveDirectory = File::copyDirectory(public_path($tempPath), public_path($this->getImageEditorPath()));
        if($moveDirectory) {
            File::deleteDirectory(public_path($tempPath));
            return str_replace($tempPath, $this->getImageEditorPath(), $this->description);
        }
    }

    /**
     * Получение временного пути для загрузки изображения
     *
     * @return string
     */
    public function getTempPath() {
        return '/uploads/temp/' . Str::random(20) . '/';
    }

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/';
	}
}
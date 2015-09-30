<?php

/**
 * Honor
 *
 * @property integer $id 
 * @property string $key
 * @property string $alias
 * @property string $title
 * @property string $image 
 * @property string $description 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $users 
 * @method static \Illuminate\Database\Query\Builder|\Honor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereDescription($value)
 */
class Honor extends \Eloquent
{
	protected $table = 'honors';

	public $timestamps = false;

	protected $fillable = [
		'alias',
		'title',
		'image',
		'description',
	];

	public function getValidationRules()
	{
		return [
			'alias' => 'max:100|regex:/^[A-Za-z0-9\-\']+$/u',
			'title' => 'required|unique:honors,title,' . $this->id . '|max:100',
			'image' => 'mimes:jpeg,bmp,png|max:3072',
			'description' => 'max:2000',
		];
	}

	public static $rules = [
		'alias' => 'max:100|regex:/^[A-Za-z0-9\-\']+$/u',
		'title' => 'required|unique:honors|max:100',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'description' => 'max:500',
	];

	public static function boot()
	{
		parent::boot();

		static::saving(function($model)
		{
			if(is_null($model->key)) {
				TranslitHelper::generateAlias($model, true);
			}
		});

		static::deleted(function($model)
		{
			$model->honorUsers()->delete();
			File::delete(public_path() . '/uploads/' . $model->getTable() . '/' . $model->image);
		});
	}

	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Пользователи, имеющие награду
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users()
	{
		return $this->belongsToMany('User', 'users_honors')->orderBy('users_honors.created_at', 'DESC');
	}

	public function honorUsers()
	{
		return $this->hasMany('UserHonor', 'honor_id');
	}

	/**
	 * Получение изображения
	 *
	 * @param null $prefix
	 * @param array $options
	 * @return string
	 */
	public function getImage($prefix = null, $options = [])
	{
		if(isset($options['class'])) {
			$options['class'] = ($this->image) ? 'img-responsive ' . $options['class'] : 'img-responsive image-default ' . $options['class'];
		} else {
			$options['class'] = ($this->image) ? 'img-responsive' : 'img-responsive image-default';
		}
		$prefix = is_null($prefix) ? '' : ($prefix . '_');
		if($this->image){
			return HTML::image('/uploads/' . $this->getTable() . '/' . $prefix . $this->image, $this->title, $options);
		} else {
			return HTML::image(Config::get('settings.' . $prefix . 'defaultHonorImage'), $this->title, $options);
		}
	}

	/**
	 * Загрузка изображения
	 *
	 * @param $postImage
	 * @return string
	 */
	public function setImage($postImage)
	{
		if(isset($postImage)){

			$fileName = TranslitHelper::generateFileName($postImage->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $this->getTable() . '/';
			$image = Image::make($postImage->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			// delete old image
			if(File::exists($imagePath . $this->image)) {
				File::delete($imagePath . $this->image);
			}

			$newFileName = TranslitHelper::make($this->title) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();
			$image->crop($cropSize, $cropSize)
				->resize(300, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . $newFileName);

			return $newFileName;
		} else {
			return $this->image;
		}
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
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/editor/';
	}

}
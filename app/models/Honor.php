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
 * @property string $meta_title
 * @property string $meta_desc
 * @property string $meta_key
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\Honor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereMetaTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereMetaDesc($value)
 * @method static \Illuminate\Database\Query\Builder|\Honor whereMetaKey($value)
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
		'meta_title',
		'meta_desc',
		'meta_key',
	];

	public function getValidationRules()
	{
		return [
			'alias' => 'max:100|regex:/^[A-Za-z0-9\-\']+$/u',
			'title' => 'required|unique:honors,title,' . $this->id . '|max:100',
			'image' => 'mimes:jpeg,bmp,png|max:2048',
			'description' => 'max:2000',
			'meta_title' => 'max:600',
			'meta_desc' => 'max:1500',
			'meta_key' => 'max:1500',
		];
	}

	public static $rules = [
		'alias' => 'max:100|regex:/^[A-Za-z0-9\-\']+$/u',
		'title' => 'required|unique:honors|max:100',
		'image' => 'mimes:jpeg,bmp,png|max:2048',
		'description' => 'max:2000',
		'meta_title' => 'max:600',
		'meta_desc' => 'max:1500',
		'meta_key' => 'max:1500',
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
		return $this->belongsToMany('User', 'users_honors')
			->select(DB::raw('*, count(users_honors.honor_id) as awardsNumber'))
			->orderBy('users_honors.created_at', 'DESC')
			->groupBy(['users_honors.honor_id', 'users_honors.user_id']);
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
	public function getImage($prefix = null, $options = [], $itemprop = false)
	{
		if(isset($options['class'])) {
			$options['class'] = ($this->image) ? 'img-responsive ' . $options['class'] : 'img-responsive image-default ' . $options['class'];
		} else {
			$options['class'] = ($this->image) ? 'img-responsive' : 'img-responsive image-default';
		}
		if($itemprop && false !== $options) {
			$options['itemprop'] = 'image';
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
				->resize(Config::get('settings.maxHonorImageWidth'), null, function ($constraint) {
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
        }
	    return str_replace($tempPath, $this->getImageEditorPath(), $this->description);
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

	public function getMetaTitle()
	{
		return $this->meta_title ? $this->meta_title : $this->title . Config::get('settings.metaTitle');
	}

	public function getMetaDesc()
	{
		return $this->meta_desc
			? $this->meta_desc
			: ($this->description
			    ? StringHelper::limit($this->description, 255, '')
			    : Config::get('settings.metaDesc')
			);
	}

	public function getMetaKey()
	{
		return $this->meta_key
			? $this->meta_key
			: ($this->description
				? StringHelper::autoMetaKeywords($this->title . ' ' . $this->description)
				: Config::get('settings.metaKey')
			);
	}

}
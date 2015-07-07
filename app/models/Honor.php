<?php

/**
 * Honor
 *
 * @property integer $id 
 * @property string $alias 
 * @property string $title 
 * @property string $image 
 * @property string $description 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $users 
 * @method static \Illuminate\Database\Query\Builder|\Honor whereId($value)
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

	public static $rules = [
		'alias' => 'max:100|regex:/^[A-Za-z0-9\-\']+$/u',
		'title' => 'required|max:100',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'description' => 'max:500',
	];

	public static function boot()
	{
		parent::boot();

		static::saving(function($model)
		{
			TranslitHelper::generateAlias($model, true);
		});

		static::deleted(function($model)
		{
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
		return $this->belongsToMany('User', 'users_honors');
	}

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
}
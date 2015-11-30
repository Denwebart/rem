<?php

/**
 * UserImage
 *
 * @property integer $id
 * @property boolean $is_published
 * @property string $title
 * @property string $image
 * @property string $image_alt
 * @property string $description
 * @property integer $votes_like
 * @property integer $votes_dislike
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $published_at
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereIsPublished($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereTitle($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereImage($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereImageAlt($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereDescription($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereVotesLike($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereVotesDislike($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage wherePublishedAt($value) 
 */

class UserImage extends \Eloquent
{
	protected $table = 'users_images';

	protected $fillable = [
		'is_published',
		'user_id',
		'title',
		'image',
		'image_alt',
		'description',
		'published_at',
	];

	public static $rules = [
		'user_id' => 'required|integer',
		'is_published' => 'boolean',
		'title' => 'max:300',
		'image' => 'required|mimes:jpeg,bmp,png|max:2048',
		'description' => '',
		'votes_like' => 'integer',
		'votes_dislike' => 'integer',
	];

	public static $rulesEdit = [
		'user_id' => 'required|integer',
		'is_published' => 'boolean',
		'title' => 'max:300',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'description' => '',
		'votes_like' => 'integer',
		'votes_dislike' => 'integer',
	];

	public static function boot()
	{
		parent::boot();

		static::saving(function($model)
		{
			$model->title = StringHelper::mbUcFirst($model->title);
		});

		static::deleted(function($model)
		{
			File::delete(public_path() . '/uploads/' . $model->getTable() . '/' . $model->user->getLoginForUrl() . '/' . $model->image);
		});
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
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
			$options['class'] = 'img-responsive ' . $options['class'];
		} else {
			$options['class'] = 'img-responsive';
		}
		$options['title'] = $this->title
			? $this->title
			: StringHelper::limit(trim(strip_tags($this->description)), 200);
		if($this->image){
			return HTML::image($this->getImageLink($prefix), $options['title'], $options);
		}
	}

	/**
	 * Получение ссылки на изображение
	 * @param null $prefix
	 * @return string
	 */
	public function getImageLink($prefix = null) {
		$prefix = is_null($prefix) ? '' : ($prefix . '_');
		return '/uploads/' . $this->table . '/' . $this->user->getLoginForUrl() . '/' . $prefix . $this->image;
	}

	/**
	 * Загрузка изображения
	 *
	 * @param $postImage
	 * @param $user
	 * @return mixed|string
	 */
	public function setImage($postImage, $user)
	{
		if(isset($postImage)){

			$fileName = TranslitHelper::generateFileName($postImage->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $this->getTable() . '/' . $user->getLoginForUrl() . '/';

			$image = Image::make($postImage->getRealPath());

			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			// delete old image
			if (File::exists($imagePath . $this->image)) {
				File::delete($imagePath . $this->image);
			}

			if(Config::get('settings.maxImageWidth') && $image->width() > Config::get('settings.maxImageWidth')) {
				$image->resize(Config::get('settings.maxImageWidth'), null, function ($constraint) {
						$constraint->aspectRatio();
					});
			}
			if(Config::get('settings.maxImageHeight') && $image->height() > Config::get('settings.maxImageHeight')) {
				$image->resize(null, Config::get('settings.maxImageHeight'), function ($constraint) {
						$constraint->aspectRatio();
					});
			}
			$image->save($imagePath . $fileName);

			return $fileName;
		} else {
			return $this->image;
		}
	}

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->user->getLoginForUrl() . '/editor/';
	}

}
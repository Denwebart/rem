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
		'image' => 'required|mimes:jpeg,bmp,png|max:3072',
		'desctiption' => 'max:2000',
		'votes_like' => 'integer',
		'votes_dislike' => 'integer',
	];

	public static $rulesEdit = [
		'user_id' => 'required|integer',
		'is_published' => 'boolean',
		'title' => 'max:300',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'desctiption' => 'max:2000',
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
			File::delete(public_path() . '/uploads/' . $model->getTable() . '/' . $model->user->login . '/' . $model->image);
		});
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function getImageUrl($user) {
		return URL::to('/uploads/'. $this->table . '/' . $user->login . '/' . $this->image);
	}
}
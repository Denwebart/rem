<?php

/**
 * Page
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $user_id
 * @property boolean $is_published
 * @property string $alias
 * @property boolean $is_container
 * @property boolean $show_submenu
 * @property boolean $show_comments
 * @property string $menu_title
 * @property string $title
 * @property string $image
 * @property string $image_alt
 * @property integer $views
 * @property integer $voters
 * @property integer $votes
 * @property string $introtext
 * @property string $content
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $published_at
 * @property string $meta_title
 * @property string $meta_desc
 * @property string $meta_key
 * @method static \Illuminate\Database\Query\Builder|\Page whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereParentId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereIsPublished($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereAlias($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereIsContainer($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereShowSubmenu($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereShowComments($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereMenuTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereTitle($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereImage($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereImageAlt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereViews($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereVoters($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereVotes($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereIntrotext($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereContent($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page wherePublishedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereMetaTitle($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereMetaDesc($value) 
 * @method static \Illuminate\Database\Query\Builder|\Page whereMetaKey($value) 
 */

class Page extends \Eloquent
{
	protected $table = 'pages';

	public $publishedTime;

	public $show_rating;
	public $show_comments;

	protected $fillable = [
		'parent_id',
		'user_id',
		'is_published',
		'alias',
		'menu_title',
		'is_container',
		'show_submenu',
		'image',
		'image_alt',
		'title',
		'introtext',
		'content',
		'published_at',
		'meta_title',
		'meta_desc',
		'meta_key',
	];

	public static $rules = [
		'parent_id' => 'integer',
		'user_id' => 'required|integer',
		'is_published' => 'boolean',
		'alias' => 'max:300',
		'menu_title' => 'required_without_all:title|max:200',
		'is_container' => 'boolean',
		'show_submenu' => 'boolean',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'image_alt' => 'max:1000',
		'title' => 'required_without_all:menu_title|max:500',
		'introtext' => 'max:2000',
		'views' => 'integer',
		'votes' => 'integer',
		'voters' => 'integer',
		'meta_title' => 'max:600',
		'meta_desc' => 'max:1500',
		'meta_key' => 'max:1500',
	];

	public function parent()
	{
		return $this->belongsTo('Page', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('Page', 'parent_id');
	}

	public function publishedChildren()
	{
		return $this->hasMany('Page', 'parent_id')
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'));
	}

	public function comments()
	{
		return $this->hasMany('Comment', 'page_id');
	}

	public function publishedComments()
	{
		return $this->hasMany('Comment', 'page_id')
			->whereIsPublished(1);
	}

	public static function boot()
	{
		parent::boot();

		static::saving(function($model)
		{
			TranslitHelper::generateAlias($model);
		});

	}

	public function getUrl()
	{
		$parentUrl = ($this->parent) ? $this->parent->getUrl() . '/' : '';
//		if($this->parent) {
//			$parent = $this->parent;
//			$parentParentUrl = ($parent->parent) ? $parent->parent->alias . '/' : '';
//		} else {
//			$parentParentUrl = '';
//		}
		return /*$parentParentUrl . */ $parentUrl . $this->alias;
	}

	public function getTitle()
	{
		return ($this->menu_title) ? $this->menu_title : $this->title;
	}

	public function getRating()
	{
		return ($this->voters) ? round($this->votes / $this->voters, 2) : "0";
	}

	public function getIntrotext()
	{
		return ($this->introtext) ? $this->introtext : StringHelper::closeTags(Str::limit($this->content, 500, '...'));
	}

	public static function getContainer()
	{
		return ['' => 'Нет'] + self::whereIsContainer(1)->lists('menu_title', 'id');
	}

	public function showComments() {
		return ($this->show_comments) ? $this->show_comments : ((!$this->is_container) ? true : false);
	}

	public function showRating() {
		return ($this->show_rating) ? $this->show_rating : ((!$this->is_container) ? true : false);
	}

	public function showViews() {
		return ($this->show_rating) ? $this->show_rating : ((!$this->is_container) ? true : false);
	}

	public function scopeGetPageByAlias($query, $alias = '/')
	{
		return $query->whereAlias($alias)
			->whereIsPublished(1)
			->where('published_at', '<', date('Y-m-d H:i:s'));
	}

}
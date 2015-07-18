<?php

/**
 * Page
 *
 * @property integer $id
 * @property integer $type
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
 * @method static \Illuminate\Database\Query\Builder|\Page whereType($value)
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

	const TYPE_PAGE = 1;
	const TYPE_QUESTIONS = 2;
	const TYPE_QUESTION = 3;
	const TYPE_JOURNAL = 4;
	const TYPE_ARTICLE = 5;

	public static $types = [
		self::TYPE_PAGE => 'Страница',
		self::TYPE_QUESTION => 'Вопрос',
		self::TYPE_ARTICLE => 'Статья',
	];

	public $publishedTime;

	public $show_rating;
	public $show_comments;

	protected $fillable = [
		'type',
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
		'type' => 'integer',
		'parent_id' => 'integer',
		'user_id' => 'required|integer',
		'is_published' => 'boolean',
		'alias' => 'max:300|regex:/^[A-Za-z0-9\-\']+$/u',
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

	public static $rulesForUsers = [
		'type' => 'integer',
		'parent_id' => 'required|integer',
		'user_id' => 'required|integer',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'title' => 'required|max:500',
		'content' => 'required',
		'meta_title' => 'max:600',
		'meta_desc' => 'max:1500',
		'meta_key' => 'max:1500',
	];

	public static function boot()
	{
		parent::boot();

		static::saving(function($page)
		{
			TranslitHelper::generateAlias($page);
			$page->title = StringHelper::mbUcFirst($page->title);
		});

		static::deleted(function($page)
		{
			// удаление похожих при удалении
			$page->relatedPages()->delete();
			File::deleteDirectory(public_path() . '/uploads/' . $page->getTable() . '/' . $page->id . '/');
		});

		static::updated(function($page)
		{
			// подписка
			if(Auth::check()) {
				$originalModel = $page->getOriginal();
				if(isset($originalModel['views'])) {
					if($page->views == $originalModel['views']) {
						if(Page::TYPE_QUESTION == $page->type) {
							$message = 'Вопрос "<a href="' . URL::to($page->getUrl()) . '">' . $page->getTitle() . '</a>" изменен.';
							SubscriptionNotification::addNotification($page, $message);
						}
					}
				}
			}
		});
	}

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

	/**
	 * Запись в таблице related_pages
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function relatedPages()
	{
		return $this->hasMany('RelatedPage', 'page_id');
	}
	/**
	 * Похожие статьи
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function relatedArticles()
	{
		return $this->belongsToMany('Page', 'related_pages', 'page_id', 'related_page_id')
			->where('related_pages.type', '=', RelatedPage::TYPE_ARTICLE)
			->orderBy('related_pages.created_at', 'ASC');
	}

	/**
	 * Похожие вопросы
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function relatedQuestions()
	{
		return $this->belongsToMany('Page', 'related_pages', 'page_id', 'related_page_id')
			->where('related_pages.type', '=', RelatedPage::TYPE_QUESTION)
			->orderBy('related_pages.created_at', 'ASC');
	}

	/**
	 * Все комментарии.
	 * @return mixed
	 */
	public function comments()
	{
		return $this->hasMany('Comment', 'page_id')->whereIsAnswer(0);
	}

	/**
	 * Опубликованные комментарии.
	 * @return mixed
	 */
	public function publishedComments()
	{
		return $this->hasMany('Comment', 'page_id')
			->whereIsAnswer(0)
			->whereIsPublished(1);
	}

	/**
	 * Опубликованные ответы.
	 * @return mixed
	 */
	public function publishedAnswers()
	{
		return $this->hasMany('Comment', 'page_id')
			->whereIsAnswer(1)
			->whereIsPublished(1);
	}

	/**
	 * Лучшие комментарии.
	 * @return mixed
	 */
	public function bestComments()
	{
		return $this->hasMany('Comment', 'page_id')
			->whereIsPublished(1)
			->whereMark(Comment::MARK_BEST);
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	/**
	 * Подписчики страницы
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subscribers()
	{
		return $this->hasMany('Subscription', 'page_id');
	}

	/**
	 * Пользователи, добавившие страницу в "Сохранённые"
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function whoSaved()
	{
		return $this->belongsToMany('User', 'users_pages');
	}

	/**
	 * Теги
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
	 */
	public function tags()
	{
		return $this->belongsToMany('Tag', 'pages_tags')->orderBy('title', 'ASC');
	}

	public function tagsLine()
	{
		$line = '';
		foreach ($this->tags as $tag) {
			$line .= $tag->title . ',';
		}
		return $line;
	}

	public function getUrl($sufix = '.html')
	{
		if(self::TYPE_ARTICLE != $this->type) {
			$sufix = (0 == $this->is_container && $this->alias != '/') ? $sufix : '';
			$parentUrl = (0 != $this->parent_id) ? (($this->parent) ? $this->parent->getUrl() . '/' : '') : '';
			return $parentUrl . $this->alias . $sufix;
		}
		else {
			$parentUrl = ($this->parent) ? $this->parent->alias : '';
			return $parentUrl . '/' . $this->user->getLoginForUrl() . '/' . $this->alias . $sufix;
		}
	}

	public function getTitle()
	{
		return ($this->menu_title) ? $this->menu_title : $this->title;
	}

	public function getTitleForBreadcrumbs()
	{
		$maxLength = 65;
		if($this->parent) {
			$parentLength = Str::length($this->parent->getTitle());
			$length = $maxLength - $parentLength;
		} else {
			$length = $maxLength;
		}
		return Str::limit($this->getTitle(), $length);
	}

	public function getContentWithWidget()
	{
		$result = preg_replace_callback('#\[\[(.+?)\]\]#is', function($matches) {
			preg_match('/([0-9]+)/', $matches[1], $id);

			$access = Auth::check() ? Advertising::ACCESS_FOR_REGISTERED : Advertising::ACCESS_FOR_GUEST;

			$advertising = Advertising::whereId($id[1])
			    ->whereIsActive(1)
				->whereType(Advertising::TYPE_ADVERTISING)
				->whereIn('access', [Advertising::ACCESS_FOR_ALL, $access])
				->get();

			if(Auth::check()) {
				if(Auth::user()->isAdmin()) {
					$advertising = Advertising::whereId($id[1])
						->whereType(Advertising::TYPE_ADVERTISING)
						->get();
				}
			}

			if(count($advertising)) {
				return (string) View::make('widgets.area.inContent', compact('advertising'))->render();
			} else {
				return '';
			}
		}, $this->content);
		return $result;
	}

	public function getRating()
	{
		return ($this->voters) ? round($this->votes / $this->voters, 2) : "0";
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
			return HTML::image('/uploads/' . $this->getTable() . '/' . $this->id . '/' . $prefix . $this->image, $this->image_alt, $options);
		} else {
			return HTML::image(Config::get('settings.' . $prefix . 'defaultImage'), $this->image_alt, $options);
		}
	}

	public function getIntrotext()
	{
		return ($this->introtext) ? $this->introtext : StringHelper::closeTags(Str::limit($this->content, 500, '...'));
	}

	public static function getContainer()
	{
		return ['' => 'Нет'] + self::whereIsContainer(1)->lists('menu_title', 'id');
	}

	public static function getQuestionsCategory()
	{
		$page = self::whereType(self::TYPE_QUESTIONS)->firstOrFail();
		return ['' => 'Нет'] + $page->children()->whereIsContainer(1)->lists('menu_title', 'id');
	}

	public function showComments() {
		return ($this->show_comments) ? $this->show_comments : ((!$this->is_container) ? true : false);
	}

	public function showRating() {
		return ($this->show_rating) ? $this->show_rating : ((!$this->is_container) ? true : false);
	}

	public function isLastLevel() {
		return (!$this->is_container && $this->parent) ? true : false;
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

	public function setViews()
	{
		$urlPrevious = (Session::has('user.urlPrevious')) ? Session::get('user.urlPrevious') : URL::previous();
		if(URL::current() != $urlPrevious)
		{
			DB::table('pages')->where('id', $this->id)->update(['views' => $this->views + 1]);
		}
	}

}
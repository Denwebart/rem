<?php

/**
 * Page
 *
 * @property integer $id 
 * @property boolean $type 
 * @property integer $parent_id 
 * @property integer $user_id 
 * @property boolean $is_published 
 * @property string $alias 
 * @property boolean $is_container 
 * @property boolean $is_show_title 
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
 * @property-read \Page $parent 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $children 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $publishedChildren 
 * @property-read \Illuminate\Database\Eloquent\Collection|\RelatedPage[] $relatedPages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $relatedArticles 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $relatedQuestions 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $allComments 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $comments 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $publishedComments 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $answers 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $publishedAnswers 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $bestComments 
 * @property-read \User $user 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Subscription[] $subscribers 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $whoSaved 
 * @property-read \Menu $menuItem 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Tag[] $tags 
 * @method static \Illuminate\Database\Query\Builder|\Page whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereIsPublished($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereIsContainer($value)
 * @method static \Illuminate\Database\Query\Builder|\Page whereIsShowTitle($value)
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
 * @method static \Page getPageByAlias($alias = '/')
 */

class Page extends \Eloquent
{
	protected $table = 'pages';

	const TYPE_PAGE = 1;
	const TYPE_QUESTIONS = 2;
	const TYPE_QUESTION = 3;
	const TYPE_JOURNAL = 4;
	const TYPE_ARTICLE = 5;
	const TYPE_SYSTEM_PAGE = 6;

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
		'is_container',
		'is_show_title',
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
		'is_container' => 'boolean',
		'is_show_title' => 'boolean',
		'image' => 'mimes:jpeg,bmp,png|max:3072',
		'image_alt' => 'max:1000',
		'title' => 'required|max:500',
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
			$page->introtext = StringHelper::nofollowLinks($page->introtext);
			$page->content = StringHelper::nofollowLinks($page->content);
		});

        static::deleting(function($page) {
            // удаление комментариев
            foreach($page->allComments as $comment) {
                $comment->sendNotificationsAboutDelete();
                $comment->delete();
            }
        });

		static::deleted(function($page)
		{
			// удаление похожих при удалении
			$page->relatedPages()->delete();
			// удаление подстатей при удалении
			$page->children()->delete();
			// удаление пункта меню
			$page->menuItem()->delete();
			//удаление папки с изображениями
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
			->select('id', 'related_pages.type', 'alias', 'is_container', 'parent_id', 'title')
			->with([
				'parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'parent.parent' => function($query) {
					$query->select('id', 'type', 'alias', 'is_container', 'parent_id');
				},
				'user' => function($query) {
					$query->select('id', 'login', 'alias', 'avatar', 'firstname', 'lastname', 'is_online', 'last_activity');
				},
			])
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
     * Все комментарии и ответы.
     * @return mixed
     */
    public function allComments()
    {
        return $this->hasMany('Comment', 'page_id');
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
     * Все ответы.
     * @return mixed
     */
    public function answers()
    {
        return $this->hasMany('Comment', 'page_id')->whereIsAnswer(1);
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
		return $this->belongsTo('User', 'user_id')
			->select('id', 'login', 'alias', 'avatar', 'email', 'firstname', 'lastname', 'is_online', 'last_activity', 'points');
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
	 * Пункт меню
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function menuItem()
	{
		return $this->hasOne('Menu', 'page_id');
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
			$parentUrl = ($this->parent_id != 0) ? ($this->parent) ? $this->parent->alias : '' : '';
			return $parentUrl . '/' . $this->user->getLoginForUrl() . '/' . $this->alias . $sufix;
		}
	}

	public function getTitle()
	{
		return $this->title;
	}

    public function getMetaTitle()
    {
        return $this->meta_title ? $this->meta_title : $this->title . Config::get('settings.metaTitle');
    }

    public function getMetaDesc()
    {
        return $this->meta_desc
            ? $this->meta_desc
            : ($this->content
                ? StringHelper::limit($this->getContentWithoutWidget(), 255, '')
                : ($this->introtext
                    ? StringHelper::limit($this->introtext, 255, '')
                    : Config::get('settings.metaDesc')
                ));
    }

    public function getMetaKey()
    {
        return $this->meta_key
            ? $this->meta_key
            : ($this->content
                ? StringHelper::autoMetaKeywords($this->title . ' ' . $this->content)
                : Config::get('settings.metaKey')
            );
    }

	public function getTitleForBreadcrumbs()
	{
		$maxLength = 72;
		if($this->parent_id != 0) {
			if($this->parent) {
				$title = $this->parent->menuItem
					? $this->parent->menuItem->menu_title
					: $this->parent->getTitle();
				$parentLength = Str::length($title);
				if ($this->parent->parent_id != 0) {
					if ($this->parent->parent) {
						$title = $this->parent->parent->menuItem
							? $this->parent->parent->menuItem->menu_title
							: $this->parent->parent->getTitle();
						$parentLength = $parentLength + Str::length($title);
					}
				}
				if (self::TYPE_ARTICLE == $this->type) {
					$parentLength = $parentLength + (2 * Str::length($this->user->login));
				}
				$length = $maxLength - $parentLength;
			}
		} else {
			$length = $maxLength;
		}
		$title = $this->menuItem ? $this->menuItem->menu_title : $this->getTitle();
		return Str::limit($title, $length);
	}

	public function getContentWithWidget()
	{
		$result = preg_replace_callback('#\[\[(.+?)\]\]#is', function($matches) {
			preg_match('/([0-9]+)/', $matches[1], $id);

			$access = Auth::check() ? Advertising::ACCESS_FOR_REGISTERED : Advertising::ACCESS_FOR_GUEST;

			if(Auth::check()) {
				if(Auth::user()->isAdmin()) {
					$advertising = Advertising::whereId($id[1])
						->whereType(Advertising::TYPE_ADVERTISING)
						->get(['id', 'type', 'area', 'position', 'title', 'is_show_title', 'access', 'code', 'is_active']);
				} else {
					$advertising = Advertising::whereId($id[1])
						->whereIsActive(1)
						->whereType(Advertising::TYPE_ADVERTISING)
						->whereIn('access', [Advertising::ACCESS_FOR_ALL, $access])
						->get(['id', 'type', 'area', 'position', 'title', 'is_show_title', 'access', 'code', 'is_active']);
				}
			} else {
				$advertising = Advertising::whereId($id[1])
					->whereIsActive(1)
					->whereType(Advertising::TYPE_ADVERTISING)
					->whereIn('access', [Advertising::ACCESS_FOR_ALL, $access])
					->get(['id', 'type', 'area', 'position', 'title', 'is_show_title', 'access', 'code', 'is_active']);
			}

			if(count($advertising)) {
				return (string) View::make('widgets.area.inContent', compact('advertising'))->render();
			} else {
				return '';
			}
		}, StringHelper::addFancybox($this->content, 'group-content'));
		return $result;
	}

	public function getContentWithoutWidget()
	{
		$result = preg_replace_callback('#\[\[(.+?)\]\]#is', function() {
			return '';
		}, $this->content);

		return $result;
	}

	public function getRating()
	{
		return ($this->voters) ? round($this->votes / $this->voters, 1) : "0";
	}

	public function getIntrotext()
	{
		return ($this->introtext)
			? $this->introtext
			: StringHelper::closeTags(Str::limit($this->getContentWithoutWidget(), 500, '...'));
	}

	public static function getContainer($withChildren = true, $withEmptyField = true)
	{
		$categoriesArray = [];
        $pages = self::whereIsContainer(1)->whereParentId(0)->with('menuItem')->where('type', '!=', self::TYPE_SYSTEM_PAGE)->get();
		foreach ($pages as $page) {
			$categoriesArray[$page->id] = $page->menuItem ? $page->menuItem->menu_title : $page->title;
			if($withChildren && $page->type != Page::TYPE_JOURNAL) {
				foreach($page->children()->whereIsContainer(1)->get() as $child) {
					$categoriesArray[$child->id] = ' --- ' . ($child->menuItem ? $child->menuItem->menu_title : $child->title);
				}
			}
		}
		return (($withEmptyField) ? ['0' => 'Нет'] : []) + $categoriesArray;
	}

	public static function getQuestionsCategory()
	{
		$page = self::whereType(self::TYPE_QUESTIONS)->firstOrFail();
		$items = $page->children()->with('menuItem')->whereIsContainer(1)->get();

		$result = ['' => 'Нет'];
		foreach($items as $item) {
			$result[$item->id] = $item->menuItem ? $item->menuItem->menu_title : $item->title;
		}
		return $result;
//		return  + $page->children()->whereIsContainer(1)->lists('title', 'id');
	}

	public function showComments() {
		return ($this->show_comments) ? $this->show_comments : ((!$this->is_container) ? true : false);
	}

	public function showRating() {
		return ($this->show_rating) ? $this->show_rating : ((!$this->is_container) ? true : false);
	}

	public function isLastLevel() {
		return (!$this->is_container && $this->parent_id != 0) ? true : false;
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

	/**
	 * Получение изображения
	 *
	 * @param null $prefix
	 * @param array $options
	 * @return string
	 */
	public function getImage($prefix = null, $options = [])
	{
		$options['title'] = $this->image_alt ? $this->image_alt : $this->getTitle();
		if(isset($options['class'])) {
			$options['class'] = ($this->image) ? 'img-responsive ' . $options['class'] : 'img-responsive image-default ' . $options['class'];
		} else {
			$options['class'] = ($this->image) ? 'img-responsive' : 'img-responsive image-default';
		}
		if($this->image){
			return HTML::image($this->getImageLink($prefix), $options['title'], $options);
		} else {
			$prefix = is_null($prefix) ? '' : ($prefix . '_');
			return HTML::image(Config::get('settings.' . $prefix . 'defaultImage'), $options['title'], $options);
		}
	}

	/**
	 * Получение ссылки на изображение
	 * @param null $prefix
	 * @return string
	 */
	public function getImageLink($prefix = null) {
		$prefix = is_null($prefix) ? '' : ($prefix . '_');
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/' . $prefix . $this->image;
	}

	public function getImagePath()
	{
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/';
	}

	/**
	 * Загрузка изображения
	 *
	 * @param $postImage
	 * @return mixed|string
	 */
	public function setImage($postImage)
	{
		if(isset($postImage)){

			$fileName = TranslitHelper::generateFileName($postImage->getClientOriginalName());

			$imagePath = public_path() . '/uploads/' . $this->getTable() . '/' . $this->id . '/';
			$image = Image::make($postImage->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			// delete old image
			$this->deleteImage();

			$watermark = Image::make(public_path('images/watermark.png'));
			$watermark->resize(($image->width() * 2) / 3, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . 'watermark.png');

			if($image->width() > 225) {
				$image->insert($imagePath . 'watermark.png', 'center')
					->save($imagePath . 'origin_' . $fileName)
					->resize(225, null, function ($constraint) {
						$constraint->aspectRatio();
					})
					->save($imagePath . $fileName);
			} else {
				$image->insert($imagePath . 'watermark.png', 'center')
					->save($imagePath . $fileName);
			}
			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();

			$image->crop($cropSize, $cropSize)
				->resize(50, null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . 'mini_' . $fileName);

			if(File::exists($imagePath . 'watermark.png')) {
				File::delete($imagePath . 'watermark.png');
			}

			return $fileName;
		} else {
			return $this->image;
		}
	}

	/**
	 * Перемещение изображений из временной папки
	 *
	 * @param $tempPath
     * @param string $field
     * @return mixed
	 */
	public function saveEditorImages($tempPath, $field = 'content')
	{
		$moveDirectory = File::copyDirectory(public_path($tempPath), public_path($this->getImageEditorPath()));
		if($moveDirectory) {
			File::deleteDirectory(public_path($tempPath));
		}
        return str_replace($tempPath, $this->getImageEditorPath(), $this->$field);
	}

	/**
	 * Удаление изображения
	 */
	public function deleteImage()
	{
		$imagePath = public_path() . '/uploads/' . $this->getTable() . '/' . $this->id . '/';

		// delete old image
		if(File::exists($imagePath . $this->image)) {
			File::delete($imagePath . $this->image);
		}
		if(File::exists($imagePath . 'origin_' . $this->image)){
			File::delete($imagePath . 'origin_' . $this->image);
		}
		if(File::exists($imagePath . 'mini_' . $this->image)){
			File::delete($imagePath . 'mini_' . $this->image);
		}

		$this->image = null;
		$this->save();
	}

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/editor/';
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
	 * Получение пути для загрузки изображения для комментариев через редактор
	 *
	 * @return string
	 */
	public function getCommentImagePath() {
		return '/uploads/' . (new Comment)->getTable() . '/page-' . $this->id . '/';
	}

	public function isEditable()
	{
		return ($this->published_at < \Carbon\Carbon::now()->subHours(1))
			? false
			: true;
	}

}
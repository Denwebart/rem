<?php

/**
 * Tag
 *
 * @property integer $id 
 * @property string $image 
 * @property string $title 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Page[] $pages 
 * @property-read \Illuminate\Database\Eloquent\Collection|\PageTag[] $pagesTags 
 * @method static \Illuminate\Database\Query\Builder|\Tag whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Tag whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Tag whereTitle($value)
 */
class Tag extends \Eloquent
{
	protected $table = 'tags';

	public $timestamps = false;

	protected $fillable = [
		'image',
		'title',
	];

	public static $rules = [
		'image' => 'mimes:jpeg,bmp,png|max:2048',
		'title' => 'required|unique:tags,title,:id|max:100',
	];

	public static $messages = [
		'title.unique' => 'Такой тег уже существует.',
	];

	public static function rules($id = false, $merge = [])
	{
		$rules = Tag::$rules;
		if ($id) {
			foreach ($rules as &$rule) {
				$rule = str_replace(':id', $id, $rule);
			}
		}
		return array_merge($rules, $merge);
	}

	public static function boot()
	{
		parent::boot();

		static::deleted(function($tag)
		{
			$tag->pagesTags()->delete();
			File::delete(public_path() . '/uploads/' . $tag->getTable() . '/' . $tag->image);

			// очистка кэша
			Cache::forget('widgets.tags');
		});

		static::saving(function($tag)
		{
			$tag->title = mb_strtolower($tag->title);
		});

		static::updated(function($tag)
		{
			// очистка кэша
			if(count($tag->pages)) {
				Cache::forget('widgets.tags');
			}
		});
	}

	public function pages()
	{
		return $this->belongsToMany('Page', 'pages_tags');
	}

	public function pagesTags()
	{
		return $this->hasMany('PageTag', 'tag_id');
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
			return HTML::image(Config::get('settings.' . $prefix . 'defaultTagImage'), $this->title, $options);
		}
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

			$imagePath = public_path() . '/uploads/' . $this->getTable() . '/';
			$image = Image::make($postImage->getRealPath());
			File::exists($imagePath) or File::makeDirectory($imagePath, 0755, true);

			// delete old image
			if(File::exists($imagePath . $this->image)) {
				File::delete($imagePath . $this->image);
			}

			$cropSize = ($image->width() < $image->height()) ? $image->width() : $image->height();
			$image->crop($cropSize, $cropSize)
				->resize(Config::get('settings.maxTagImageWidth'), null, function ($constraint) {
					$constraint->aspectRatio();
				})->save($imagePath . $fileName);

			return $fileName;
		} else {
			return $this->image;
		}
	}

	public static function getByAlphabet()
	{
		$tags = self::orderBy('title', 'ASC')
			->whereHas('pages', function($query) {
				$query->whereIsPublished(1)->where('published_at', '<', date('Y-m-d H:i:s'));
			})->with(['pages' => function($query) {
				$query->select('id', 'is_published', 'published_at');
			}])->get();

		$tagsByAlphabet = [];
		foreach ($tags as $item) {
			$codeArray = preg_split('//u', mb_strtoupper($item->title), -1, PREG_SPLIT_NO_EMPTY);
			$tagsByAlphabet[$codeArray[0]][] = $item;
		}
		return $tagsByAlphabet;
	}

	/**
	 * Добавление тегов (прикрепить к статье)
	 *
	 * @param $page
	 * @param $addedArray
	 */
	public static function addTag($page, $addedArray)
	{
        if($addedArray) {
            $tags = $page->tags()->lists('title', 'id');

            $newTagsIds = [];
            if(isset($addedArray['newTags'])) {
                $newTags = array_unique(array_map("mb_strtolower", $addedArray['newTags']));
                foreach($newTags as $key => $title) {
                    $tag = Tag::whereTitle($title)->first();
                    if(!is_object($tag)) {
                        $tag = Tag::create(['title' => $title]);
                    }
                    $newTagsIds[$tag->id] = $title;
                }
            }

            unset($addedArray['new'], $addedArray['newTags']);
            $added = array_diff($newTagsIds + $addedArray, $tags);

            $dataAdded = [];
            if($added) {
                foreach($added as $id => $title) {
                    $dataAdded[] = [
                        'page_id' => $page->id,
                        'tag_id' => $id,
                    ];
                }
            }

            if(count($dataAdded)) {
                DB::table('pages_tags')->insert($dataAdded);
	            Cache::forget('widgets.tags');
            }
        }
	}

	/**
	 * Удаление тегов (открепить от статьи)
	 *
	 * @param $page
	 * @param $deletedArray
	 */
	public static function deleteTag($page, $deletedArray)
	{
        if($deletedArray) {
            $tags = $page->tags()->lists('title', 'id');
            unset($deletedArray['new'], $deletedArray['newTags']);
            $deleted = array_diff($tags, $deletedArray);
            if(count($deleted)) {
                PageTag::wherePageId($page->id)
                    ->whereIn('tag_id', array_flip($deleted))
                    ->delete();
	            Cache::forget('widgets.tags');
            }
        }
	}

}
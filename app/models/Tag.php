<?php

class Tag extends \Eloquent
{
	protected $table = 'tags';

	public $timestamps = false;

	protected $fillable = [
		'image',
		'title',
	];

	public static $rules = [
		'image' => 'mimes:jpeg,bmp,png|max:1024',
		'title' => 'required|unique:tags,title,:id|max:100',
	];

	public static $messages = array(
		'title.unique' => 'Такой тег уже существует.',
	);

	public static function rules($id = false, $merge = [])
	{
		$rules = self::$rules;
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

	public static function getByAlphabet()
	{
		$tags = self::orderBy('title', 'ASC')->has('pages')->with('pages')->get();

		$tagsByAlphabet = [];
		foreach ($tags as $item) {
			$codeArray = preg_split('//u', mb_strtoupper($item->title), -1, PREG_SPLIT_NO_EMPTY);
			$tagsByAlphabet[$codeArray[0]][] = $item;
		}
		return $tagsByAlphabet;
	}

	/**
	 * Добавление тегов
	 *
	 * @param $page
	 * @param $addedArray
	 */
	public static function addTag($page, $addedArray)
	{
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
		}
	}

	/**
	 * Удаление тегов
	 *
	 * @param $page
	 * @param $deletedArray
	 */
	public static function deleteTag($page, $deletedArray)
	{
		$tags = $page->tags()->lists('title', 'id');
		unset($deletedArray['new'], $deletedArray['newTags']);
		$deleted = array_diff($tags, $deletedArray);
		if(count($deleted)) {
			PageTag::wherePageId($page->id)
				->whereIn('tag_id', array_flip($deleted))
				->delete();
		}
	}

}
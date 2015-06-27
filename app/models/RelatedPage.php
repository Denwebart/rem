<?php


/**
 * RelatedPage
 *
 * @property integer $page_id 
 * @property \Page $related_page_id 
 * @property boolean $type 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Page $page 
 * @method static \Illuminate\Database\Query\Builder|\RelatedPage wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\RelatedPage whereRelatedPageId($value)
 * @method static \Illuminate\Database\Query\Builder|\RelatedPage whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\RelatedPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\RelatedPage whereUpdatedAt($value)
 */
class RelatedPage extends Eloquent {

	protected $table = 'related_pages';

	protected $primaryKey = array('page_id','related_page_id');

	public $incrementing = false;

	const TYPE_ARTICLE = 1;
	const TYPE_QUESTION = 2;

	protected $fillable = [
		'page_id',
		'related_page_id',
		'type',
	];

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}

	public function related_page_id()
	{
		return $this->belongsTo('Page', 'related_page_id');
	}

	/**
	 * Добавление похожих статей/вопросов
	 *
	 * @param $page
	 * @param $addedArray
	 * @param $type
	 */
	public static function addRelated($page, $addedArray, $type)
	{
		$related = (self::TYPE_QUESTION == $type)
			? $page->relatedQuestions()->lists('id', 'id')
			: $page->relatedArticles()->lists('id', 'id');

		$added = array_diff($addedArray, $related);
		unset($added['new']);
		$dataAdded = [];
		if($added) {
			foreach($added as $item) {
				$dataAdded[] = [
					'page_id' => $page->id,
					'related_page_id' => $item,
					'type' => $type,
					'created_at' => date('Y:m:d H:i:s'),
				];
			}
		}
		if(count($dataAdded)) {
			DB::table('related_pages')->insert($dataAdded);
		}
	}

	/**
	 * Удаление похожих статей/вопросов
	 *
	 * @param $page
	 * @param $deletedArray
	 * @param $type
	 */
	public static function deleteRelated($page, $deletedArray, $type)
	{
		$related = (self::TYPE_QUESTION == $type)
			? $page->relatedQuestions()->lists('id', 'id')
			: $page->relatedArticles()->lists('id', 'id');

		$deleted = array_diff($related, $deletedArray);
		unset($deleted['new']);

		if(count($deleted)) {
			RelatedPage::wherePageId($page->id)
				->whereIn('related_page_id', $deleted)
				->delete();
		}
	}
}
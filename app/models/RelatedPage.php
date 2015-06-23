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

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}

	public function related_page_id()
	{
		return $this->belongsTo('Page', 'related_page_id');
	}
}
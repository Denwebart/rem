<?php

/**
 * Comment
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $page_id
 * @property boolean $is_published
 * @property integer $votes_like
 * @property integer $votes_dislike
 * @property string $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $published_at
 * @property string $read_at
 * @method static \Illuminate\Database\Query\Builder|\Comment whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereParentId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment wherePageId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereIsPublished($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereVotesLike($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereVotesDislike($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereComment($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment wherePublishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereReadAt($value)
 */

class Comment extends \Eloquent
{
	const VOTE_LIKE = 'like';
	const VOTE_DISLIKE = 'dislike';

	protected $table = 'comments';

	protected $fillable = [
		'parent_id',
		'user_id',
		'page_id',
		'is_published',
		'comment',
		'published_at',
	];

	public static $rules = [
		'page_id' => 'required|numeric',
		'parent_id' => 'required|numeric',
		'user_id' => 'required|numeric',
		'is_published' => 'boolean',
		'comment' => 'required',
	];

	public function children()
	{
		return $this->hasMany('Comment', 'parent_id');
	}

	public function publishedChildren()
	{
		return $this->hasMany('Comment', 'parent_id')->whereIsPublished(1);
	}

	public function parent()
	{
		return $this->belongsTo('Comment', 'parent_id');
	}

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function getUrl()
	{
		return URL::to($this->page->getUrl() . '#comment-' . $this->id);
	}
}
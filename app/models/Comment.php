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
 */

class Comment extends \Eloquent
{
	protected $table = 'comments';

	protected $fillable = [
		'parent_id',
		'user_id',
		'page_id',
		'is_published',
		'comment',
		'published_at',
	];
}
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
}
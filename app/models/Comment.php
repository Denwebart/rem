<?php

/**
 * Comment
 *
 * @property integer $id
 * @property boolean $is_answer
 * @property integer $parent_id
 * @property integer $user_id
 * @property string $user_email
 * @property string $user_name
 * @property string $user_ip
 * @property integer $page_id
 * @property boolean $is_published
 * @property integer $votes_like
 * @property integer $votes_dislike
 * @property string $comment
 * @property integer $mark
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $published_at
 * @property string $read_at
 * @method static \Illuminate\Database\Query\Builder|\Comment whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereIsAnswer($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserName($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserIp($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereIsPublished($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereVotesLike($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereVotesDislike($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereComment($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereMark($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUpdatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\Comment wherePublishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereReadAt($value)
 */

class Comment extends \Eloquent
{
	const VOTE_LIKE = 'like';
	const VOTE_DISLIKE = 'dislike';

	const MARK_BEST = 1;

	protected $table = 'comments';

	protected $fillable = [
		'is_answer',
		'parent_id',
		'user_id',
		'user_name',
		'user_ip',
		'user_email',
		'page_id',
		'is_published',
		'comment',
		'published_at',
	];

	public static $rules = [
		'is_answer' => 'boolean',
		'page_id' => 'required|numeric',
		'parent_id' => 'required|numeric',
		'user_id' => 'required_without_all:user_name,user_email|numeric',
		'user_name' => 'required_without_all:user_id|max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
		'user_email' => 'required_without_all:user_id|email|max:150',
		'user_ip' => 'ip',
		'is_published' => 'boolean',
		'comment' => 'required',
		'g-recaptcha-response' => 'required_without_all:user_id|captcha'
	];

	public static $rulesForUpdate = [
		'is_published' => 'boolean',
		'comment' => 'required',
	];

	public static function boot()
	{
		parent::boot();

		/**
		 * Подписка
		 */
		static::saved(function($comment)
		{
			if(Page::TYPE_QUESTION == $comment->page->type) {
				if(0 == $comment->parent_id) {
					$message = 'Добавлен новый ответ к вопросу "<a href="' . URL::to($comment->getUrl()) . '">' . $comment->page->getTitle() . '</a>".';
				} else {
					$message = 'Добавлен новый комментарий к вопросу "<a href="' . URL::to($comment->getUrl()) . '">' . $comment->page->getTitle() . '</a>".';
				}
				SubscriptionNotification::addNotification($comment->page, $message);
			} elseif(Page::TYPE_ARTICLE == $comment->page->type) {
				$message = 'Добавлен новый комментарий к статье "<a href="' . URL::to($comment->getUrl()) . '">' . $comment->page->getTitle() . '</a>".';
				SubscriptionNotification::addNotification($comment->page, $message);
			}
		});
	}

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

	public function ip()
	{
		return $this->belongsTo('Ip', 'ip_id');
	}

	public function getUrl()
	{
		return ($this->page) ? URL::to($this->page->getUrl() . '#comment-' . $this->id) : '';
	}

	public function isEditable()
	{
		return ($this->created_at < \Carbon\Carbon::now()->subHours(1))
			? false
			: true;
	}

	/**
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/';
	}
}
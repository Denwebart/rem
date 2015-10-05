<?php

/**
 * Comment
 *
 * @property integer $id 
 * @property boolean $is_answer 
 * @property integer $parent_id 
 * @property integer $user_id 
 * @property integer $ip_id 
 * @property string $user_email 
 * @property string $user_name 
 * @property integer $page_id 
 * @property boolean $is_published 
 * @property boolean $is_deleted 
 * @property integer $votes_like 
 * @property integer $votes_dislike 
 * @property string $comment 
 * @property boolean $mark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $read_at 
 * @property string $published_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $children 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Comment[] $publishedChildren 
 * @property-read \Comment $parent 
 * @property-read \Page $page 
 * @property-read \User $user 
 * @property-read \Ip $ip 
 * @method static \Illuminate\Database\Query\Builder|\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereIsAnswer($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereIpId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUserName($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereIsPublished($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereIsDeleted($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereVotesLike($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereVotesDislike($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereMark($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment whereReadAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Comment wherePublishedAt($value)
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
		'ip_id',
		'user_email',
		'page_id',
		'is_published',
		'comment',
		'published_at',
	];

	const STATUS_NOT_PUBLISHED = 0;
	const STATUS_PUBLISHED = 1;
	const STATUS_DELETED = 2;

	public static $status = [
		self::STATUS_PUBLISHED => 'Опубликован',
		self::STATUS_NOT_PUBLISHED => 'Не опубликован',
		self::STATUS_DELETED => 'Удалён',
	];

	public static $rules = [
		'is_answer' => 'boolean',
		'page_id' => 'required|numeric',
		'parent_id' => 'required|numeric',
		'user_id' => 'required_without_all:user_name,user_email|numeric',
		'user_name' => 'required_without_all:user_id|max:150|regex:/^[A-Za-zА-Яа-яЁёЇїІіЄєЭэ \-\']+$/u',
		'user_email' => 'required_without_all:user_id|email|max:150',
		'ip_id' => 'numeric',
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
     * Перемещение изображений из временной папки
     *
     * @param $tempPath
     * @return mixed
     */
    public function saveEditorImages($tempPath)
    {
        $moveDirectory = File::copyDirectory(public_path($tempPath), public_path($this->getImageEditorPath()));
        if($moveDirectory) {
            File::deleteDirectory(public_path($tempPath));
        }
	    return str_replace($tempPath, $this->getImageEditorPath(), $this->comment);
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
	 * Получение пути для загрузки изображения через редактор
	 *
	 * @return string
	 */
	public function getImageEditorPath() {
		return '/uploads/' . $this->getTable() . '/' . $this->id . '/';
	}

	/**
	 * Отметить комментарий как удаленный
	 */
	public function markAsDeleted()
	{
		if(!is_null($this->user)) {
			if($this->is_answer) {
				$this->user->removePoints(User::POINTS_FOR_ANSWER);
				if($this->mark == Comment::MARK_BEST) {
					$this->user->removePoints(User::POINTS_FOR_BEST_ANSWER);
					$this->user->setNotification(Notification::TYPE_POINTS_FOR_BEST_ANSWER_REMOVED, [
						'[answer]' => strip_tags($this->comment),
						'[linkToAnswer]' => URL::route('user.answers', [
								'login' => $this->user->getLoginForUrl()
							]) . '#answer-' . $this->id,
						'[pageTitle]' => $this->page->getTitle(),
						'[linkToPage]' => URL::to($this->page->getUrl())
					]);
				} else {
					$this->user->setNotification(Notification::TYPE_ANSWER_DELETED, [
						'[answer]' => strip_tags($this->comment),
						'[linkToAnswer]' => URL::route('user.answers', [
								'login' => $this->user->getLoginForUrl()
							]) . '#answer-' . $this->id,
						'[pageTitle]' => $this->page->getTitle(),
						'[linkToPage]' => URL::to($this->page->getUrl())
					]);
				}
			} else {
				$this->user->removePoints(User::POINTS_FOR_COMMENT);
				$this->user->setNotification(Notification::TYPE_COMMENT_DELETED, [
					'[comment]' => strip_tags($this->comment),
					'[linkToComment]' => URL::route('user.comments', [
							'login' => $this->user->getLoginForUrl()
						]) . '#comment-' . $this->id,
					'[pageTitle]' => $this->page->getTitle(),
					'[linkToPage]' => URL::to($this->page->getUrl())
				]);
			}
		}

		$this->is_deleted = 1;
		$this->save();
	}

	/**
	 * Восстановить комментарий
	 */
	public function markAsUndeleted()
	{
		$this->is_deleted = 0;
		$this->save();
	}
}
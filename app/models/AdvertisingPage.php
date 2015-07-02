<?php

/**
 * AdvertisingPage
 *
 * @property integer $id
 * @property integer $advertising_id
 * @property integer $page_type
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\UserImage whereAdvertisingId($value)
 * @method static \Illuminate\Database\Query\Builder|\UserImage wherePageType($value)
 */

class AdvertisingPage extends \Eloquent
{
	protected $table = 'advertising_pages';

	public $timestamps = false;

	const PAGE_ALL = 0;
	const PAGE_MAIN = 1;
	const PAGE_PAGE = 2;
	const PAGE_SEARCH = 3;
	const PAGE_QUESTIONS = 4;
	const PAGE_QUESTIONS_CATEGORY = 5;
	const PAGE_QUESTION = 6;
	const PAGE_JOURNAL = 7;
	const PAGE_USER_JOURNAL = 8;
	const PAGE_ARTICLE = 9;
	const PAGE_CABINET = 10;

	public static $pages = [
		self::PAGE_ALL => 'На всех страницах сайта',
		self::PAGE_MAIN => 'На главной странице',
		self::PAGE_PAGE => 'На страницах сайта',
		self::PAGE_SEARCH => 'На страницах поиска',
		self::PAGE_QUESTIONS => 'На странице "Вопрос-ответ"',
		self::PAGE_QUESTIONS_CATEGORY => 'На категории вопросов',
		self::PAGE_QUESTION => 'На вопросах',
		self::PAGE_JOURNAL => 'На странице "Бортовой журнал"',
		self::PAGE_USER_JOURNAL => 'В журнале пользователя',
		self::PAGE_ARTICLE => 'На статьях в журнале',
		self::PAGE_CABINET => 'В личном кабинете',
	];

	protected $fillable = [
		'advertising_id',
		'page_type',
	];

	public static $rules = [
		'advertising_id' => 'required|numeric',
		'page_type' => 'numeric|min:0|max:10',
	];

	public function advertising()
	{
		return $this->belongsTo('Advertising', 'advertising_id');
	}

	public static function add($advertising, $pages)
	{
		AdvertisingPage::whereAdvertisingId($advertising->id)->delete();
		$advertisingPageData = [];
		foreach($pages as $pageType => $value) {
			$advertisingPageData[] = [
				'advertising_id' => $advertising->id,
				'page_type' => $pageType,
			];
		}
		DB::table('advertising_pages')->insert($advertisingPageData);
	}

}
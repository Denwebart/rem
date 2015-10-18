<?php

/**
 * Menu
 *
 * @property integer $id 
 * @property boolean $type 
 * @property integer $page_id 
 * @property integer $position
 * @property string $menu_title 
 * @method static \Illuminate\Database\Query\Builder|\Menu whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereMenuTitle($value)
 */

class Menu extends \Eloquent
{
	protected $table = 'menus';
	public $timestamps = false;

	const TYPE_MAIN = 1;
	const TYPE_TOP = 2;
	const TYPE_BOTTOM = 3;

	public static $types = [
		self::TYPE_MAIN => 'Главное меню',
		self::TYPE_TOP => 'Верхнее меню',
		self::TYPE_BOTTOM => 'Нижнее меню',
	];

	protected $fillable = [
		'type',
		'page_id',
		'position',
		'menu_title',
	];

	public static $rules = [
		'type' => 'required|integer',
		'page_id' => 'required|integer',
		'position' => 'required|integer',
		'menu_title' => 'required|max:200',
	];

	public function page()
	{
		return $this->belongsTo('Page', 'page_id');
	}

	public function children()
	{
		return $this->hasMany('Menu', 'parent_id')->orderBy('position', 'ASC');
	}

	public function parent()
	{
		return $this->belongsTo('Menu', 'parent_id');
	}

	public function getTitle()
	{
		return $this->menu_title ? $this->menu_title : $this->page->getTitle();
	}

	public static function inMenu($page)
	{
		$menuItem = Menu::wherePageId($page->id)->first();
		if($page->is_published) {
			if(Page::TYPE_SYSTEM_PAGE == $page->type || Page::TYPE_QUESTIONS == $page->type || Page::TYPE_JOURNAL == $page->type || Page::TYPE_PAGE == $page->type) {
				if($page->parent_id == 0) {
					Menu::create(['type' => self::TYPE_MAIN, 'page_id' => $page->id, 'menu_title' => $page->title]);
				} else {
					if($page->is_container) {
						$parentMenuItem = Menu::wherePageId($page->parent_id)->first();
						if($parentMenuItem) {
							Menu::create(['type' => self::TYPE_MAIN, 'page_id' => $page->id, 'menu_title' => $page->title, 'parent_id' => $parentMenuItem->id]);
						}
					}
				}
			}
		} else {
			if($menuItem) {
				$menuItem->delete();
			}
		}
	}
}
<?php

/**
 * Menu
 *
 * @property integer $id 
 * @property boolean $type 
 * @property integer $page_id 
 * @property integer $position
 * @method static \Illuminate\Database\Query\Builder|\Menu whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Menu wherePosition($value)
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
		'parent_id',
		'position',
	];

	public static $rules = [
		'type' => 'required|integer',
		'page_id' => 'required|integer',
		'position' => 'required|integer',
	];

	public static function boot()
	{
		parent::boot();

		static::saved(function($item)
		{
		    if($item->type == Menu::TYPE_TOP) Cache::forget('menu.top');
			if($item->type == Menu::TYPE_BOTTOM) Cache::forget('menu.bottom');

			if($item->type == Menu::TYPE_MAIN) {
				Cache::forget('menu.main');

				if($item->parent_id != 0) {
					if($item->page) {
						Cache::forget('widgets.sidebar.' . $item->page->parent_id);
				    }
				} else {
					Cache::forget('widgets.sidebar.' . $item->page_id);
				}
			}
		});

		static::deleted(function($item)
		{
			if($item->type == Menu::TYPE_TOP) Cache::forget('menu.top');
			if($item->type == Menu::TYPE_BOTTOM) Cache::forget('menu.bottom');

			if($item->type == Menu::TYPE_MAIN) {
				Cache::forget('menu.main');

				if($item->parent_id != 0) {
					if($item->page) {
						Cache::forget('widgets.sidebar.' . $item->page->parent_id);
					}
				} else {
					Cache::forget('widgets.sidebar.' . $item->page_id);
				}
			}
		});
	}

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

	public static function inMenu($page)
	{
		$menuItem = Menu::wherePageId($page->id)->first();
		if($page->is_published) {
			if(Page::TYPE_SYSTEM_PAGE == $page->type || Page::TYPE_QUESTIONS == $page->type || Page::TYPE_JOURNAL == $page->type || Page::TYPE_PAGE == $page->type) {
				if($page->parent_id == 0 && $page->is_container == 1) {
					if(!$menuItem) {
						Menu::create(['type' => self::TYPE_MAIN, 'page_id' => $page->id]);
					}
				} else {
					if($page->is_container) {
						$parentMenuItem = Menu::wherePageId($page->parent_id)->first();
						if($parentMenuItem) {
							if(!$menuItem) {
								Menu::create(['type' => self::TYPE_MAIN, 'page_id' => $page->id, 'parent_id' => $parentMenuItem->id]);
							} else {
								Menu::wherePageId($page->id)->update(['parent_id' => $parentMenuItem->id]);
							}
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
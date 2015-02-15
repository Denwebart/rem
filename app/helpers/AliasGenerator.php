<?php

class AliasGenerator
{
	protected static $translitArray = [
		'ый' => 'y',
		'ье' => 'ye',

		'а'=>'a',
		'б'=>'b',
		'в'=>'v',
		'г'=>'g',
		'д'=>'d',
		'е'=>'e',
		'ё'=>'e',
		'ж'=>'zh',
		'з'=>'z',
		'и'=>'i',
		'й'=>'j',
		'к'=>'k',
		'л'=>'l',
		'м'=>'m',
		'н'=>'n',
		'о'=>'o',
		'п'=>'p',
		'р'=>'r',
		'с'=>'s',
		'т'=>'t',
		'у'=>'u',
		'ф'=>'f',
		'х'=>'h',
		'ц'=>'ts',
		'ч'=>'ch',
		'ш'=>'sh',
		'щ'=>'shch',
		'ъ'=>'',
		'ы'=>'y',
		'ь'=>'',
		'э'=>'e',
		'ю'=>'yu',
		'я'=>'ya',
		'і' => 'i',
		'ї' => 'i',
		'є' => 'e',
		'ґ' => 'g',
		' '=>'-',
	];

	public static function generate($model)
	{
		if(!$model->alias)
		{
			$text = preg_replace('/[^a-zа-яёіїєґ0-9- ]+/iu', '', preg_replace('/\s+/', ' ', trim(strip_tags(html_entity_decode(mb_strtolower($model->getTitle()))))));

			foreach(self::$translitArray as $from => $to) {
				$text = mb_eregi_replace($from, $to, $text);
			}

			$model->alias = $text;

			return true;
		}

		return false;
	}
}

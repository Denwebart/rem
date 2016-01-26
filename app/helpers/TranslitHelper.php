<?php

class TranslitHelper
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
		'_' => '-',
	];

	public static function generateAlias($model, $rewrite = false)
	{
		if(!$rewrite) {
			//fаlse
			if(!$model->alias)
			{
				$model->alias = self::make($model->getTitle());
				return true;
			}
			return false;
		} else {
			//true
			$model->alias = self::make($model->getTitle());
			return true;
		}
	}

	public static function generateFileName($fileName)
	{
		return self::make($fileName, '/[^a-zа-яёіїєґ0-9-._ ]+/iu');
	}

	public static function make($string, $pattern = '/[^a-zа-яёіїєґ0-9-_ ]+/iu')
	{
		$text = preg_replace($pattern, '', preg_replace('/\s+/', ' ', trim(strip_tags(html_entity_decode(mb_strtolower($string))))));

		foreach(self::$translitArray as $from => $to) {
			$text = mb_eregi_replace($from, $to, $text);
		}

		return $text;
	}
}

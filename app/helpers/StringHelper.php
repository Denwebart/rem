<?php

class StringHelper
{
	/**
	 * Поиск фрагмента текста с искомым словом,
	 * подсветка искомого слова
	 *
	 * @param type $text
	 * @param type $word
	 * @return type
	 */
	public static function getFragment($text, $word)
	{
		if ($word) {
			$pos = max(mb_stripos(strip_tags($text), $word, null, 'UTF-8') - 100, 0);
			$fragment = mb_substr(strip_tags($text), $pos, 200, 'UTF-8');
			$highlighted = preg_replace("[(".quotemeta($word).")]iu", '<mark>$1</mark>', $fragment);
		} else {
			$highlighted = mb_substr(strip_tags($text), 0, 200, 'UTF-8');
		}
		return $highlighted;
	}
}

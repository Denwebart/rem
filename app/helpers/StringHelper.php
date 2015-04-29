<?php

class StringHelper
{
	/**
	 * Searching text fragment with concrete word and marking it
	 *
	 * @param string $text
	 * @param string $word
	 * @return string
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

	/**
	 * Обрезка фрагмента текста до пробела с учетом кодировки
	 *
	 * @param $html
	 * @param $size
	 * @return string
	 */
	public static function limit($html, $size, $end = '...'){
		$string = strip_tags($html);
		return mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $size,'utf-8'),' ', 'utf-8'),'utf-8') . $end;
	}

	/**
	 * Closing html tags which not closed
	 *
	 * @param string $html
	 * @return string
	 */
	public static function closeTags($html)
	{
		preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
		$openedtags = $result[1];
		preg_match_all('#</([a-z]+)>#iU', $html, $result);
		$closedtags = $result[1];
		$len_opened = count($openedtags);
		if (count($closedtags) == $len_opened) {
			return $html;
		}
		$openedtags = array_reverse($openedtags);
		for ($i=0; $i < $len_opened; $i++) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= '</'.$openedtags[$i].'>';
			} else {
				unset($closedtags[array_search($openedtags[$i], $closedtags)]);
			}
		}
		return $html;
	}

	/**
	 * Adding attribute rel=nofollow to the links
	 *
	 * @param string $html
	 * @return string
	 */
	public static function nofollowLinks($html)
	{
		$html = preg_replace("~<a.*?</a>(*SKIP)(*F)|(http|https|ftp|ftps)://([^\s\[<]+)~i", '<a href="$1://$2">$1://$2</a>', $html);
		return preg_replace_callback('/<a href="(.*?)"(.*?)>/', [new StringHelper(), 'checkLinksAndReplace'], $html);
	}

	/**
	 * Checking links for adding attribute
	 * rel="nofollow" and target="_blank" if link is referal
	 *
	 * @param $link
	 * @return string
	 */
    public function checkLinksAndReplace($link)
    {
	    if($link[1][0]=='/' || (strpos($link[1], Config::get('settings.siteUrl'))!==false) ) {
		    return $link[0];
	    }
	    else {
		    return '<a href="'.$link[1].'" rel="nofollow" target="_blank">';
	    }
	}

	/**
	 * Автоматическая генерация мета-тега keywords из текста
	 *
	 * @param $html
	 * @param $limit
	 * @return string
	 */
	public static function autoMetaKeywords($html, $limit = 10) {
		$withoutLinks = preg_replace("~<a.*?</a>(*SKIP)(*F)|(http|https|ftp|ftps)://([^\s\[<]+)~i", '', StringHelper::limit($html, 500, ''));
		$string = preg_replace('/ {2,}/', ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $withoutLinks));

		$words = explode(' ',  mb_strtolower($string));

		$excludeWords = [
			'а-ля', 'а',
			'без',
			'в', 'возле', 'ввиду',
			'для', 'до',
			'за',
			'из', 'из-за', 'из-под',
			'к', 'ко', 'кроме', 'как', 'когда', 'кто', 'кто-то', 'кто-нибудь',
			'меж', 'между', 'мимо',
			'на', 'над', 'наподобие', 'напротив', 'ниже',
			'о', 'об', 'обо', 'около', 'от',
			'по', 'под', 'подобно', 'подо', 'помимо', 'после', 'посреди', 'при', 'про', 'против', 'помогите', 'пожалуйста', 'подскажите',
			'с', 'сверху', 'сзади', 'сквозь', 'следом', 'снизу', 'со', 'согласно', 'спустя', 'среди', 'судя', 'сколько',
			'у', 'увы',
			'через', 'что', 'что-то', 'чтобы', 'часто',
			'это',
			'я',
		];

		$words = array_diff($words, $excludeWords);

		$keywordsArray =[];
		foreach ($words as $key => $word){
			if(mb_strlen($word) > 3){
				$keywordsArray[$key] = preg_replace('/[^A-Za-zА-Яа-яЁёЇїІіЄє-]/u', '', $word);
			}
		}

		$keywordsArray = array_slice($keywordsArray, 0, $limit);
		$keywords = implode(', ', $keywordsArray);

		return $keywords;
	}

}
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
	 * @param $end
	 * @return string
	 */
	public static function limit($html, $size, $end = '...')
	{
		if(mb_strlen($html) > $size) {
			$string = strip_tags($html);
			return mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $size,'utf-8'),' ', 'utf-8'),'utf-8') . $end;
		} else {
			return strip_tags($html);
		}
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
		$html = preg_replace("~<a.*?</a>(*SKIP)(*F)|<img.*?>(*SKIP)(*F)|(http|https|ftp|ftps)://([^\s\[<]+)~i", '<a href="$1://$2">$1://$2</a>', $html);
		return preg_replace_callback('/<a(.*?)href="(.*?)"(.*?)>/', [new StringHelper(), 'checkLinksAndReplace'], $html);
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
	    if($link[2][0] == '/' || (strpos($link[2], Config::get('settings.siteUrl')) !== false)) {
		    return '<a' . $link[1] . 'href="' . $link[2] . '" '. $link[3] .'>';
	    }
	    else {
		    if (!preg_match("~^(?:f|ht)tps?://~i", $link[2])) {
			    $link[2] = "http://" . $link[2];
		    }
		    return '<a' . $link[1] . 'href="' . $link[2] . '" rel="nofollow" target="_blank">';
	    }
	}

	/**
	 * Автоматическая генерация мета-тега keywords из текста
	 *
	 * @param $html
	 * @param $limit
	 * @return string
	 */
	public static function autoMetaKeywords($html, $limit = 10, $delimiter = ', ') {
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

		$keywordsArray =[];
		foreach ($words as $key => $word){
			if(mb_strlen($word) > 3){
				$keywordsArray[$key] = preg_replace('/[^0-9A-Za-zА-Яа-яЁёЇїІіЄє-]/u', '', $word);
			}
		}
		$keywordsArray = array_diff(array_diff(array_unique($keywordsArray), $excludeWords), ['']);
		$keywordsArray = array_slice($keywordsArray, 0, $limit);
		$keywords = implode($delimiter, $keywordsArray);

		return $keywords;
	}

	/**
	 * Uppercase first letter. Working with multi-byte encodings.
	 *
	 * @param $str
	 * @param string $encoding
	 * @return string
	 */
	public static function mbUcFirst($str, $encoding = 'UTF-8')
	{
		return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding)
		. mb_substr($str, 1, null, $encoding);
	}

	/**
	 * Добавление Fancybox к изображениям в тексте.
	 *
	 * @param $html
	 * @return mixed
	 */
	public static function addFancybox($html, $group = false)
	{
		return preg_replace_callback('/(<img(.+?)src="(.*?)"(.+?)>)/iu', function($image) use($group) {
			$group = ($group) ? 'data-fancybox-group="'. $group .'"' : '';
			if(!strpos($image[3], '/emoticons/img/smiley')) {
				$imageTag = '<img itemprop="image" '. $image[2] . 'src="'. $image[3] .'"' . $image[4] . '>';
				return '<a href="' . $image[3] . '" class="fancybox" '. $group .'>' . $imageTag . '</a>';
			} else {
				return $image[0];
			}
		}, $html);
	}

	/**
	 * Удаление ссылок из текста
	 *
	 * @param $html
	 * @return mixed
	 */
	public static function withoutLinks($html)
	{
		return str_replace('</a>', '', preg_replace('/<a\b[^>]*+>|<\/a\b[^>]*+>/', '', $html));
	}

	/**
	 * Формат байтов
	 *
	 * @param $fsizebyte
	 * @return string
	 */
	public static function fileSize($fsizebyte) {
		if ($fsizebyte < 1024) {
			$fsize = $fsizebyte." bytes";
		}elseif (($fsizebyte >= 1024) && ($fsizebyte < 1048576)) {
			$fsize = round(($fsizebyte/1024), 2);
			$fsize = $fsize." KB";
		}elseif (($fsizebyte >= 1048576) && ($fsizebyte < 1073741824)) {
			$fsize = round(($fsizebyte/1048576), 2);
			$fsize = $fsize." MB";
		}elseif ($fsizebyte >= 1073741824) {
			$fsize = round(($fsizebyte/1073741824), 2);
			$fsize = $fsize." GB";
		};
		return $fsize;
	}
}
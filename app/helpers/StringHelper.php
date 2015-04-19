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

}
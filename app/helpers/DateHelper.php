<?php

class DateHelper
{
    protected static $months = [
		'1' => 'января',
		'2' => 'февраля',
		'3' => 'марта',
		'4' => 'апреля',
		'5' => 'мая',
		'6' => 'июня',
		'7' => 'июля',
		'8' => 'августа',
		'9' => 'сентября',
		'10' => 'октября',
		'11' => 'ноября',
		'12' => 'декабря',
	];

	protected static $shortMonths = [
		'1' => 'янв.',
		'2' => 'февр.',
		'3' => 'марта',
		'4' => 'апр.',
		'5' => 'мая',
		'6' => 'июня',
		'7' => 'июля',
		'8' => 'авг.',
		'9' => 'сент.',
		'10' => 'окт.',
		'11' => 'нояб.',
		'12' => 'дек.',
	];

	public static function dateFormat($date, $isShortMonth = true)
	{
		$timestamp = strtotime($date);
		$month = ($isShortMonth) ?
			self::$shortMonths[date('n', $timestamp)] : self::$sonths[date('n', $timestamp)];
		return date("d $month Y H:i", $timestamp);
	}

//	public static function getRelativeTime($timestamp, $headText = 'about ')
//	{
//		$delta = (time() - $timestamp);
//		if ($delta < 0) {
//			return $headText . '0 seconds ago';
//		}
//		$r = '';
//		if ($delta < 60) {
//			$r = round($delta, 0) . ' seconds ago';
//		} else if ($delta < 120) {
//			$r = 'a minute ago';
//		} else if ($delta < (45 * 60)) {
//			$r = round(($delta / 60), 0) . ' minutes ago';
//		} else if ($delta < (2 * 60 * 60)) {
//			$r = 'an hour ago';
//		} else if ($delta < (24 * 60 * 60)) {
//			$r = '' . round(($delta / 3600), 0) . ' hours ago';
//		} else if ($delta < (48 * 60 * 60)) {
//			$r = 'a day ago';
//		} else {
//			$r = round(($delta / 86400), 0) . ' days ago';
//		}
//		return $headText . $r;
//	}

}
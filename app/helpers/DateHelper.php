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

	/**
	 * Формат даты для всего сайта
	 *
	 * @param $date Дата
	 * @param bool $withTime Если нужно время
	 * @param bool $isShortMonth Месяц сокращен
	 * @return string
	 */
	public static function dateFormat($date, $withTime = true, $isShortMonth = true)
	{
		$timestamp = strtotime($date);
		$month = ($isShortMonth) ?
			self::$shortMonths[date('n', $timestamp)] : self::$sonths[date('n', $timestamp)];
		$time = ($withTime) ? " H:i" : "";
		return date("d $month Y" . $time, $timestamp);
	}

	/**
	 * Время в формате "1 мин. наазд" и т.д
	 *
	 * @param $date Дата
	 * @return string
	 */
	public static function getRelativeTime($date)
	{
		$delta = (time() - strtotime($date));
		$endText = ' назад';

		if ($delta < 0) {
			return '0 сек.';
		}
		if ($delta < 60) {
			$result = round($delta, 0) . ' сек.' . $endText;
		} elseif ($delta < 120) {
			$result = '1 мин.' . $endText;
		} elseif ($delta < (45 * 60)) {
			$result = round(($delta / 60), 0) . ' мин.' . $endText;
		} elseif ($delta < (2 * 60 * 60)) {
			$result = '1 ч.' . $endText;
		} elseif ($delta < (24 * 60 * 60)) {
			$result = round(($delta / 3600), 0) . ' ч.' . $endText;
		} elseif ($delta < (48 * 60 * 60)) {
			$result = '1 д.' . $endText;
		} else {
			$result = round(($delta / 86400), 0) . ' д.' . $endText;
		}

//		if ($delta < 60) {
//			$result = round($delta, 0) . ' сек.' . $endText;
//		} elseif ($delta < 120) {
//			$result = '1 мин.' . $endText;
//		} elseif ($delta < (45 * 60)) {
//			$result = round(($delta / 60), 0) . ' мин.' . $endText;
//		} elseif ($delta < (2 * 60 * 60)) {
//			$result = '1 час' . $endText;
//		} elseif ($delta < (5 * 60 * 60)) {
//			$result = round(($delta / 3600), 0) . ' часа' . $endText;
//		} elseif ($delta < (24 * 60 * 60)) {
//			$result = round(($delta / 3600), 0) . ' часов' . $endText;
//		} elseif ($delta < (48 * 60 * 60)) {
//			$result = '1 день' . $endText;
//		} elseif ($delta < (24 * 5 * 60 * 60)) {
//			$result = round(($delta / 86400), 0) . ' дня' . $endText;
//		} else {
//			$result = round(($delta / 86400), 0) . ' дней' . $endText;
//		}
		return $result;
	}

}
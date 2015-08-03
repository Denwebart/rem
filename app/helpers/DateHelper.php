<?php

class DateHelper
{
	public static $monthsList = [
		'1' => 'Январь',
		'2' => 'Февраль',
		'3' => 'Март',
		'4' => 'Апрель',
		'5' => 'Май',
		'6' => 'Июнь',
		'7' => 'Июль',
		'8' => 'Август',
		'9' => 'Сентябрь',
		'10' => 'Октябрь',
		'11' => 'Ноябрь',
		'12' => 'Декабрь',
	];

    protected static $months = [
		'1' => 'Января',
		'2' => 'Февраля',
		'3' => 'Марта',
		'4' => 'Апреля',
		'5' => 'Мая',
		'6' => 'Июня',
		'7' => 'Июля',
		'8' => 'Августа',
		'9' => 'Сентября',
		'10' => 'Октября',
		'11' => 'Ноября',
		'12' => 'Декабря',
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
	 * @param string $date Дата
	 * @param bool $withTime Если нужно время
	 * @param bool $isShortMonth Месяц сокращен
	 * @return string
	 */
	public static function dateFormat($date, $withTime = true, $isShortMonth = true)
	{
		if(!is_null($date)) {
			$timestamp = strtotime($date);
			$month = ($isShortMonth) ?
				self::$shortMonths[date('n', $timestamp)] : self::$months[date('n', $timestamp)];
			$time = ($withTime) ? " H:i" : "";
			return date("j $month Y" . $time, $timestamp);
		} else {
			return '-';
		}
	}

	/**
	 * Формат даты для всего сайта
	 *
	 * @param string $date Дата
	 * @param bool $withTime Если нужно время
	 * @param bool $isShortMonth Месяц сокращен
	 * @return string
	 */
	public static function date($format, $date)
	{
		$timestamp = strtotime($date);
		if('M' == $format) {
			return self::$months[date('n', $timestamp)];
		}
		return date($format, $timestamp);
	}

	/**
	 * Время в формате "1 мин. наазд" и т.д
	 *
	 * @param string $date Дата
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

    public static function dateForMessage($date)
    {
	    $timestamp = strtotime($date);

	    return date('H:i', $timestamp);
    }

}
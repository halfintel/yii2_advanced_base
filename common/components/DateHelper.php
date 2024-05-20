<?php

namespace common\components;


use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;

class DateHelper
{
    public const FORMAT_WITHOUT_TIME = "Y-m-d";

    public const FORMAT_WITHOUT_TIME_2 = "d-m-Y";

    public const FORMAT_WITHOUT_TIME_3 = "d.m.Y";

    public const FORMAT_WITHOUT_TIME_4 = "Ymd";

    public const FORMAT_WITH_TIME = "Y-m-d H:i";

    public const FORMAT_WITH_FULL_TIME = "Y-m-d H:i:s";

    public const FORMAT_GFMONEY = 'd.m.Y H:i:s';

    public const FORMAT_CREAM_FINANCE = 'm/Y';

    public const DB_TIMEZONE = 'Europe/Madrid';

    public const SERVER_TIMEZONE = 'UTC';

    public const NEW_YORK_TIMEZONE = 'America/New_York';

    public const FORMAT_ADMIN = 'd-m-Y H:i:s';

    public const FORMAT_FIESTA_CREDITO = 'Y-m-d\TH:i:sP';

    public const FORMAT_ISO_8601 = 'Y-m-d\TH:i:s';// 1968-07-12T00:00:00

    public const FORMAT_HOUR_G = 'G';

    public const FORMAT_WEEK = 'W';

    public const FORMAT_MONTH = 'm';

    public const FORMAT_DAY_OF_WEEK = 'N';

    public const FORMAT_YEAR = 'Y';

    public const FORMAT_MONTH_WITH_YEAR = 'Y_m';

    /**
     * @throws Exception
     */
    public static function getAge(string $birthday): int
    {
        $date = new DateTime($birthday);

        $now = new DateTime();

        $interval = $now->diff($date);

        return $interval->y;
    }

    public static function createDateForDb(int $timestamp): string
    {
        return date("Y-m-d H:i:s", $timestamp);
    }

    public static function createDateForGoogleAds(int $timestamp): string
    {
        return date("Y-m-d H:i:sP", $timestamp);
    }

    public static function plusOneDayWithoutTime(string $date): string
    {
        $date = strtotime($date);

        $date = strtotime("+1 day", $date);

        return date(self::FORMAT_WITHOUT_TIME, $date);
    }

    public static function getStartOfTheDay(string $date): string
    {
        $date = strtotime($date);

        return date(self::FORMAT_WITHOUT_TIME, $date) . ' 00:00:00';
    }

    /**
     * @param $date = 'now -1 day', '2023-05-25', etc
     * @return string
     */
    public static function getEndOfTheDay(string $date): string
    {
        $date = strtotime($date);

        return date(self::FORMAT_WITHOUT_TIME, $date) . ' 23:59:59';
    }

    /**
     * @param $date = 'now -1 day', '2023-05-25', etc
     * @return string
     */
    public static function getDateWithoutTime($date): string
    {
        $date = strtotime($date);

        return date(self::FORMAT_WITHOUT_TIME, $date);
    }

    /**
     * @param $date = 'now -1 day', '2023-05-25', etc
     * @return string
     */
    public static function getDateWithTime($date): string
    {
        $date = strtotime($date);

        return date(self::FORMAT_WITH_TIME, $date);
    }

    /**
     * @throws Exception
     *
     * @see DbHelper::convertDateTime()
     */
    public static function convertDateTime(string $datetime = 'now', string $format = DateHelper::FORMAT_WITH_FULL_TIME): string
    {
        $originalDateTime = new DateTime($datetime);

        return $originalDateTime->format($format);
    }

    /**
     * @throws Exception
     */
    public static function convertTimezone(string $datetime = 'now', string $fromTimezone = self::NEW_YORK_TIMEZONE, string $toTimezone = self::DB_TIMEZONE, string $format = DateHelper::FORMAT_WITH_FULL_TIME): string
    {
        $date = new DateTime($datetime, new DateTimeZone($fromTimezone));

        $date->setTimezone(new DateTimeZone($toTimezone));

        return $date->format($format);
    }


    /**
     * @throws Exception
     */
    public static function getCurrentDate(string $timezone = self::DB_TIMEZONE): string
    {
        $date = new DateTime('now', new DateTimeZone(self::SERVER_TIMEZONE));

        $date->setTimezone(new DateTimeZone($timezone));

        return $date->format(self::FORMAT_WITHOUT_TIME);
    }

    public static function isFirstDateTimeBigger(string $datetime1, string $datetime2): bool
    {
        $timestamp1 = strtotime($datetime1);

        $timestamp2 = strtotime($datetime2);

        return $timestamp1 > $timestamp2;
    }

    /**
     * @param array $datesByIds = [12 => '2023-01-16 08:00:00', 53 => '2023-01-15 08:00:00']
     * @return int 53
     */
    public static function getEarliestId(array $datesByIds): int
    {
        $minDate = PHP_INT_MAX;
        $minDateId = null;

        foreach ($datesByIds as $id => $date) {
            $currentDate = strtotime($date);

            if ($currentDate !== false && $currentDate < $minDate) {
                $minDate = $currentDate;
                $minDateId = $id;
            }
        }

        return $minDateId;
    }

    public static function getTimestamp(string $datetime): int
    {
        return strtotime($datetime);
    }

    /**
     * @param int $day 1-28 (29-31 not tested)
     */
    public static function getDateOfNextMonth(int $day, string $format = self::FORMAT_WITHOUT_TIME): string
    {
        $currentDate = new \DateTime();

        $currentDate->setDate(date('Y'), date('m'), $day);

        $currentDate->modify('+1 month');

        return $currentDate->format($format);
    }

    public static function getStartOfTheMonth(string $format = self::FORMAT_WITHOUT_TIME): string
    {
        $currentDate = new \DateTime();

        $currentDate->setDate(date('Y'), date('m'), 1);

        $currentDate->setTime(0, 0, 0);

        return $currentDate->format($format);
    }

    public static function addTime(string $datetime, string $format, int $addHours = 0, int $addDays = 0): string
    {
        $dateTime = new DateTime($datetime);

        $dateTime->add(new DateInterval("PT{$addHours}H"));

        $dateTime->add(new DateInterval("P{$addDays}D"));

        return $dateTime->format($format);
    }

    public static function getCurrentHour(): int
    {
        $dateTime = new DateTime('now');

        return $dateTime->format('H');
    }

    public static function getDateRange(string $startDate, string $endDate, string $format = self::FORMAT_WITHOUT_TIME): array
    {
        $dateRange = [];

        if (DateHelper::isFirstDateTimeBigger($startDate, $endDate)) {
            $currentDate = strtotime($endDate);

            $endDate = strtotime($startDate);

        } else {
            $currentDate = strtotime($startDate);

            $endDate = strtotime($endDate);
        }

        while ($currentDate <= $endDate) {
            $dateRange[] = date($format, $currentDate);

            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $dateRange;
    }

    public static function getDateOfStartPreviousWeek(string $format = self::FORMAT_WITHOUT_TIME): string
    {
        $currentDate = date('Y-m-d');

        $dayOfWeek = date(self::FORMAT_DAY_OF_WEEK, strtotime($currentDate));

        $daysToSubtract = $dayOfWeek - 1 + 7;

        return date($format, strtotime("-$daysToSubtract days", strtotime($currentDate)));
    }

    /**
     * @throws Exception
     */
    public static function getMaxDate(string $format = self::FORMAT_WITHOUT_TIME): string
    {
        return self::convertDateTime('now +10 year', $format);
    }

    public static function getCountDaysBetweenDates(string $date1, string $date2): int
    {
        $date1 = new DateTime($date1);

        $date2 = new DateTime($date2);

        $interval = $date1->diff($date2);

        return $interval->days;
    }
}


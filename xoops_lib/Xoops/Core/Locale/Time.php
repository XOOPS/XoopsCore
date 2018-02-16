<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Locale;

use Xoops\Core\Locale\Punic\Calendar;
use Punic\Data;
use Punic\Plural;
use Xoops\Locale;

/**
 * Xoops\Core\Locale\Time - localized time handling
 *
 * @category  Xoops\Core\Locale\Time
 * @package   Xoops
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Time
{
    /**
     * cleanTime
     *
     * @param number|\DateTime|string $time An Unix timestamp, DateTime instance or string accepted by strtotime.
     *
     * @return \DateTime
     */
    public static function cleanTime($time = null)
    {
        if (is_a($time, '\DateTime')) {
            return $time->setTimezone(Locale::getTimeZone());
        }
        if ($time === null || $time === 0 || $time === '') {
            return new \DateTime('now', Locale::getTimeZone());
        }
        return Calendar::toDateTime($time, Locale::getTimeZone());
    }

    /**
     * Describe an relative interval from $dateStart to $dateEnd (eg '2 days ago').
     * Only the largest differing unit is described, and the next smaller unit will be used
     * for rounding.
     *
     * @param \DateTime      $dateEnd   The terminal date
     * @param \DateTime|null $dateStart The anchor date, defaults to now. (if it has a timezone different than
     *                        $dateEnd, we'll use the one of $dateEnd)
     * @param string         $width     The format name; it can be '', 'short' or 'narrow'
     * @param string         $locale    The locale to use. If empty we'll use the default locale set in \Punic\Data
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public static function describeRelativeInterval($dateEnd, $dateStart = null, $width = '', $locale = '')
    {
        if (!is_a($dateEnd, '\DateTime')) {
            throw new \InvalidArgumentException('Not a DateTime object');
        }
        if (empty($dateStart) && ($dateStart !== 0) && ($dateStart !== '0')) {
            $dateStart = new \DateTime('now');
        } elseif (!is_a($dateStart, '\DateTime')) {
            throw new \InvalidArgumentException('Not a DateTime object');
        } else {
            $dateStart = clone $dateStart;
        }
        $dateStart->setTimezone($dateEnd->getTimezone());

        //$utc = new \DateTimeZone('UTC');
        //$dateEndUTC = new \DateTime($dateEnd->format('Y-m-d H:i:s'), $utc);
        //$dateStartUTC = new \DateTime($dateStart->format('Y-m-d H:i:s'), $utc);
        $parts = array();
        $data = Data::get('dateFields', $locale);

        $diff = $dateStart->diff($dateEnd, false);
        $past = (boolean) $diff->invert;
        $value = 0;
        $key = '';
        if ($diff->y != 0) {
            $key = 'year';
            $value = $diff->y + (($diff->m > 6) ? 1 : 0);
        } elseif ($diff->m != 0) {
            $key = 'month';
            $value = $diff->m + (($diff->d > 15) ? 1 : 0);
        } elseif ($diff->d != 0) {
            $key = 'day';
            $value = $diff->d + (($diff->h > 12) ? 1 : 0);
        } elseif ($diff->h != 0) {
            $key = 'hour';
            $value = $diff->h + (($diff->i > 30) ? 1 : 0);
        } elseif ($diff->i != 0) {
            $key = 'minute';
            $value = $diff->i + (($diff->s > 30) ? 1 : 0);
        } elseif ($diff->s != 0) {
            $key = 'second';
            $value = $diff->s;
        }
        if ($value==0) {
            $key = 'second';
            $relKey = 'relative-type-0';
            $relPattern = null;
        } elseif ($key === 'day' && $value >1 && $value <7) {
            $dow = $dateEnd->format('N') - 1;
            $days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
            $key = $days[$dow];
            $relKey = ($past) ? "relative-type--1" : "relative-type-1";
            $relPattern = null;
        } else {
            if ($value == 1 && isset($data[$key]['relative-type--1'])) {
                $relKey = ($past) ? 'relative-type--1' : 'relative-type-1';
                $relPattern = null;
            } else {
                $relKey = ($past) ? 'relativeTime-type-past' : 'relativeTime-type-future';
                $rule = Plural::getRule($value, $locale);
                $relPattern = 'relativeTimePattern-count-' . $rule;
            }
        }
        if (!empty($width) && array_key_exists($key . '-' . $width, $data)) {
            $key .= '-' . $width;
        }
        if (empty($relPattern)) {
            $relativeString = $data[$key][$relKey];
        } else {
            $tempString = $data[$key][$relKey][$relPattern];
            $tempString = str_replace('{0}', '%d', $tempString);
            $relativeString = sprintf($tempString, $value);
        }
        return $relativeString;
    }

    /**
     * Format a date.
     *
     * @param number|\DateTime|string $value      An Unix timestamp, a `\DateTime` instance or a string accepted
     *                                             by strtotime().
     * @param string                  $width      The format name; it can be
     *                                               'full' (eg 'EEEE, MMMM d, y' - 'Wednesday, August 20, 2014'),
     *                                               'long' (eg 'MMMM d, y' - 'August 20, 2014'),
     *                                               'medium' (eg 'MMM d, y' - 'August 20, 2014') or
     *                                               'short' (eg 'M/d/yy' - '8/20/14').
     * @param string|\DateTimeZone    $toTimezone The timezone to set; leave empty to use the default timezone
     *                                             (or the timezone associated to $value if it's already a \DateTime)
     * @param string                  $locale     The locale to use. If empty we'll use the default
     *
     * @return string Returns an empty string if $value is empty, the localized textual representation otherwise
     */
    public static function formatDate($value, $width = 'short', $toTimezone = '', $locale = '')
    {
        try {
            $formatted = Calendar::formatDateEx($value, $width, $toTimezone, $locale);
        } catch (\Punic\Exception $e) {
            \Xoops::getInstance()->events()->triggerEvent('core.exception', $e);
            $formatted = '';
        }
        return $formatted;
    }

    /**
     * Format a date.
     *
     * @param number|\DateTime|string $value      An Unix timestamp, a `\DateTime` instance or a string accepted
     *                                             by strtotime().
     * @param string                  $width      The format name; it can be
     *                                               'full' (eg 'h:mm:ss a zzzz' - '11:42:13 AM GMT+2:00'),
     *                                               'long' (eg 'h:mm:ss a z' - '11:42:13 AM GMT+2:00'),
     *                                               'medium' (eg 'h:mm:ss a' - '11:42:13 AM') or
     *                                               'short' (eg 'h:mm a' - '11:42 AM')
     * @param string|\DateTimeZone    $toTimezone The timezone to set; leave empty to use the default timezone
     *                                             (or the timezone associated to $value if it's already a \DateTime)
     * @param string                  $locale     The locale to use. If empty we'll use the default
     *
     * @return string Returns an empty string if $value is empty, the localized textual representation otherwise
     *
     * @throws \Punic\Exception Throws an exception in case of problems
     */
    public static function formatTime($value, $width = 'short', $toTimezone = '', $locale = '')
    {
        try {
            $formatted = Calendar::formatTimeEx($value, $width, $toTimezone, $locale);
        } catch (\Punic\Exception $e) {
            \Xoops::getInstance()->events()->triggerEvent('core.exception', $e);
            $formatted = '';
        }
        return $formatted;
    }

    /**
     * Format a date/time.
     *
     * @param \DateTime $value The \DateTime instance for which you want the localized textual representation
     * @param string    $width The format name; it can be 'full', 'long', 'medium', 'short' or a combination
     *                          for date+time like 'full|short' or a combination for format+date+time like
     *                          'full|full|short'
     *                          You can also append an asterisk ('*') to the date part of $width. If so,
     *                          special day names may be used (like 'Today', 'Yesterday', 'Tomorrow') instead
     *                          of the date part.
     * @param string $locale   The locale to use. If empty we'll use the default locale
     *
     * @return string Returns an empty string if $value is empty, the localized textual representation otherwise
     *
     * @throws \Punic\Exception Throws an exception in case of problems
     */
    public static function formatDateTime(\DateTime $value, $width, $locale = '')
    {
        return Calendar::formatDatetime($value, $width, $locale);
    }

    /**
     * Perform any localization required for date picker used in Form\DateSelect
     *
     * @return void
     */
    public static function localizeDatePicker()
    {
        $delimiter = '-';
        $locale = Locale::normalizeLocale(Locale::getCurrent(), $delimiter, false);
        if ('zh_Hant' === Locale::getCurrent()) {
            $locale = 'zh-TW';
        }
        if ($locale === 'zh') {
            $locale = 'zh-CN';
        }
        list($language) = explode($delimiter, $locale);
        $xoops = \Xoops::getInstance();

        $locales = array($locale, $language);
        foreach ($locales as $name) {
            $i18nScript = 'media/jquery/ui/i18n/datepicker-' . $name . '.js';
            if (file_exists($xoops->path($i18nScript))) {
                $xoops->theme()->addBaseScriptAssets($i18nScript);
                return;
            }
        }
    }

    /**
     * turn a utf8 string into an array of characters
     *
     * @param string $input string to convert
     *
     * @return array
     */
    protected static function utf8StringToChars($input)
    {
        $chars = array();
        $strLen = mb_strlen($input, 'UTF-8');
        for ($i = 0; $i < $strLen; $i++) {
            $chars[] = mb_substr($input, $i, 1, 'UTF-8');
        }
        return $chars;
    }

    /**
     * parse a date input according to a locale and apply it to a DateTime object
     *
     * @param \DateTime $datetime datetime to apply date to
     * @param string    $input    localized date string
     * @param string    $locale   optional locale to use, leave blank to use current
     *
     * @return void
     *
     * @throws \Punic\Exception\ValueNotInList
     */
    protected static function parseInputDate(\DateTime $datetime, $input, $locale = '')
    {
        $year = 0;
        $month = 0;
        $day = 0;

        $order = [];
        $dateFormat = Calendar::getDateFormat('short', $locale);
        $formatChars = static::utf8StringToChars($dateFormat);
        $state = 'non';
        $newstate = $state;
        foreach ($formatChars as $char) {
            switch ($char) {
                case 'y':
                    $newstate = 'y';
                    break;
                case 'M':
                    $newstate = 'm';
                    break;
                case 'd':
                    $newstate = 'd';
                    break;
                default:
                    $newstate = 'non';
                    break;
            }
            if ($newstate !== $state) {
                if (in_array($newstate, ['y', 'm', 'd'])) {
                    $order[] = $newstate;
                }
                $state = $newstate;
            }
        }

        $pieces = [];
        $pieceIndex = -1;
        $inputChars = static::utf8StringToChars($input);
        $state = 'non';
        $newstate = $state;
        foreach ($inputChars as $char) {
            switch ($char) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9':
                    $newstate = 'digit';
                    break;
                default:
                    $newstate = 'non';
                    break;
            }
            if ($newstate !== $state) {
                if ($newstate === 'digit') {
                    $pieces[++$pieceIndex] = $char;
                }
                $state = $newstate;
            } elseif ($state === 'digit') {
                $pieces[$pieceIndex] .= $char;
            }
        }

        foreach ($pieces as $i => $piece) {
            $piece = (int) ltrim($piece, '0');
            switch ($order[$i]) {
                case 'd':
                    $day = $piece;
                    break;
                case 'm':
                    $month = $piece;
                    break;
                case 'y':
                    $year = $piece;
                    break;
            }
        }
        if ($year < 100) {
            if ($year<70) {
                $year += 2000;
            } else {
                $year += 1900;
            }
        }
        $datetime->setDate($year, $month, $day);
        // public DateTime DateTime::setTime ( int $hour , int $minute [, int $second = 0 ] )
    }

    /**
     * parse a time input according to a locale and apply it to a DateTime object
     *
     * @param \DateTime $datetime datetime to apply time to
     * @param string    $input    localized time string
     * @param string    $locale   optional locale to use, leave blank to use current
     *
     * @return void
     *
     * @throws \Punic\Exception\BadArgumentType
     * @throws \Punic\Exception\ValueNotInList
     */
    protected static function parseInputTime(\DateTime $datetime, $input, $locale = '')
    {
        $timeFormat = Calendar::getTimeFormat('short', $locale);
        $am = Calendar::getDayperiodName('am', 'wide', $locale);
        $pm = Calendar::getDayperiodName('pm', 'wide', $locale);
        $clock12 = Calendar::has12HoursClock($locale);

        $hour = 0;
        $minute = 0;
        $second = 0;

        $order = [];
        $formatChars = static::utf8StringToChars($timeFormat);
        $state = 'non';
        $newstate = $state;
        foreach ($formatChars as $char) {
            switch ($char) {
                case 'h':
                case 'H':
                    $newstate = 'h';
                    break;
                case 'm':
                    $newstate = 'm';
                    break;
                case 'a':
                default:
                    $newstate = 'non';
                    break;
            }
            if ($newstate !== $state) {
                if (in_array($newstate, ['h', 'm'])) {
                    $order[] = $newstate;
                }
                $state = $newstate;
            }
        }

        $pieces = [];
        $pieceIndex = -1;
        $inputChars = static::utf8StringToChars($input);
        $state = 'non';
        $newstate = $state;
        foreach ($inputChars as $char) {
            switch ($char) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9':
                    $newstate = 'digit';
                    break;
                default:
                    $newstate = 'non';
                    break;
            }
            if ($newstate !== $state) {
                if ($newstate === 'digit') {
                    $pieces[++$pieceIndex] = $char;
                }
                $state = $newstate;
            } elseif ($state === 'digit') {
                $pieces[$pieceIndex] .= $char;
            }
        }

        foreach ($pieces as $i => $piece) {
            $piece = (int) ltrim($piece, '0');
            switch ($order[$i]) {
                case 'h':
                    $hour = $piece;
                    break;
                case 'm':
                    $minute = $piece;
                    break;
            }
        }
        if ($clock12) {
            if ($hour == 12 && false !== mb_strpos($input, $am)) {
                $hour = 0;
            }
            if (false !== mb_strpos($input, $pm)) {
                $hour += 12;
            }
        }
        $datetime->setTime($hour, $minute, $second);
    }

    /**
     * Convert a XOOPS DateSelect or DateTime form input into a DateTime object
     *
     * @param string|string[] $input  date string, or array of date and time strings
     * @param string          $locale optional locale to use, leave blank to use current
     *
     * @return \DateTime
     */
    public static function inputToDateTime($input, $locale = '')
    {
        $dateTime = static::cleanTime();
        $dateTime->setTime(0, 0, 0);

        if (is_array($input)) {
            static::parseInputDate($dateTime, $input['date'], $locale);
            static::parseInputTime($dateTime, $input['time'], $locale);
        } else { // single string should be just a date
            static::parseInputDate($dateTime, $input, $locale);
        }
        return $dateTime;
    }
}

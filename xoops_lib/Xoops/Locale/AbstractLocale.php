<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Locale;

use Patchwork\Utf8;
use Punic\Misc;
use Xoops\Core\Locale\Punic\Calendar;
use Xoops\Core\Locale\Time;

/**
 * XOOPS localization abstract
 *
 * @category  Xoops\Locale
 * @package   Xoops\Locale\Abstract
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
abstract class AbstractLocale
{
    /**
     * isMultiByte - does locale depend on multi-byte characters?
     *
     * @return bool true always true with UTF-8
     *
     * @deprecated since 2.6.0 -- UTF-8 is always used
     */
    public static function isMultiByte()
    {
        return true;
    }

    /**
     * isRtl - is text order right to left?
     *
     * @return bool true if right to left
     */
    public static function isRtl()
    {
        return ('right-to-left' === Misc::getCharacterOrder());
    }

    /**
     * todo, do not forget to set this on locale load
     */
    public static function setLocale()
    {
        return setlocale(LC_ALL, self::getLocale());
    }

    /**
     * getCharset - return current character set, always UTF-8
     *
     * @return string character set
     */
    public static function getCharset()
    {
        return 'UTF-8';
    }

    /**
     * getLocale - return the current locale
     *
     * @return string
     */
    public static function getLocale()
    {
        return \Xoops\Locale::getCurrent();
    }

    /**
     * getLangCode - return language code for the current locale (locale with '-' separator)
     *
     * @return string
     */
    public static function getLangCode()
    {
        return \Xoops\Locale::normalizeLocale(\Xoops\Locale::getCurrent(), '-', false);
    }

    /**
     * getLegacyLanguage - return legacy language code for the current locale
     * @return string
     */
    public static function getLegacyLanguage()
    {
        $legacyLanguages = \Xoops\Core\Locale\LegacyCodes::getLegacyName(\Xoops\Locale::getCurrent());

        return reset($legacyLanguages);
    }

    /**
     * @return string
     */
    public static function getTimezone()
    {
        return \Xoops\Locale::getTimeZone()->getName();
    }

    /**
     * The generic css fonts are:
     *  - cursive
     *  - fantasy
     *  - monospace
     *  - sans-serif
     *  - serif
     *
     * @return string[]
     */
    public static function getFonts()
    {
        return [
            'Arial',
            'Courier',
            'Georgia',
            'Helvetica',
            'Impact',
            'Verdana',
            'Haettenschweiler',
        ];
    }

    /**
     * The css should adjust based on the
     *    html:lang(ja) {
     *        font-size: 150%;
     *    }
     * Then classes can be relative to that base em
     * CJK fonts may need to be shown in a larger size due to complex glyphs
     *
     * @return array
     */
    public static function getFontSizes()
    {
        return [
            'xx-small' => 'xx-Small',
            'x-small' => 'x-Small',
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
            'x-large' => 'x-Large',
            'xx-large' => 'xx-Large',
        ];
    }

    /**
     * @return string[]
     */
    public static function getAdminRssUrls()
    {
        return ['https://xoops.org/modules/publisher/backend.php'];
    }

    /**
     * @param mixed   $str
     * @param int $start
     * @param int $length
     * @param string  $ellipsis
     *
     * @return string
     */
    public static function substr($str, $start, $length, $ellipsis = '…')
    {
        $str2 = mb_strcut($str, $start, $length - mb_strlen($ellipsis));

        return $str2 . (mb_strlen($str) - $start != mb_strlen($str2) ? $ellipsis : '');
    }

    /**
     *  filter to UTF-8, converts invalid $text as CP1252 and forces NFC normalization
     *
     * @param mixed $text
     *
     * @return string
     */
    public static function utf8_encode($text)
    {
        return Utf8::filter($text);
    }

    /**
     * @param mixed  $text
     * @param string $to
     * @param string $from
     *
     * @return string
     *
     * @deprecated
     */
    public static function convert_encoding($text, $to = 'utf-8', $from = '')
    {
        return $text;
    }

    /**
     * XoopsLocalAbstract::trim()
     *
     * @param mixed $text
     *
     * @return string
     */
    public static function trim($text)
    {
        $ret = Utf8::trim($text);

        return $ret;
    }

    /**
     * Function to display formatted times in user timezone
     *
     * @param mixed  $time
     * @param string $format Format codes ()
     *                       's' or 'short'  - short;
     *                       'm' or 'medium' - medium;
     *                       'l' or 'long'   - long;
     *                       'c' or 'custom' - format determined according to interval to present;
     *                       'e' or 'elapse' - Elapsed;
     *                       'mysql' - Y-m-d H:i:s;
     *                       'rss'
     *
     * @return string
     */
    public static function formatTimestamp($time, $format = 'l')
    {
        $workingTime = Time::cleanTime($time);

        switch (mb_strtolower($format)) {
            case 'short':
            case 's':
                return Time::formatDateTime($workingTime, 'short');
            case 'medium':
            case 'm':
                return Time::formatDateTime($workingTime, 'medium');
            case 'long':
            case 'l':
                return Time::formatDateTime($workingTime, 'long');
            case 'full':
            case 'f':
                return Time::formatDateTime($workingTime, 'full');
            case 'custom':
            case 'c':
                $specialName = Calendar::getDateRelativeName($workingTime, true);
                if ('' != $specialName) {
                    return $specialName;
                }
                // no break - fall through
            case 'elapse':
            case 'e':
                return Time::describeRelativeInterval($workingTime);
            case 'short-date':
                return Time::formatDate($workingTime, 'short');
            case 'short-time':
                return Time::formatTime($workingTime, 'short');
            case 'medium-date':
                return Time::formatDate($workingTime, 'medium');
            case 'medium-time':
                return Time::formatTime($workingTime, 'medium');
            case 'long-date':
                return Time::formatDate($workingTime, 'long');
            case 'long-time':
                return Time::formatTime($workingTime, 'long');
            case 'full-date':
                return Time::formatDate($workingTime, 'full');
            case 'full-time':
                return Time::formatTime($workingTime, 'full');
            case 'rss':
                $workingTime->setTimezone(new \DateTimeZone('UTC'));

                return $workingTime->format($workingTime::RSS);
            case 'mysql':
                $workingTime->setTimezone(new \DateTimeZone('UTC'));

                return $workingTime->format('Y-m-d H:i:s');
            default:
                if ('' != $format) {
                    return $workingTime->format($format);
                }

                return Time::formatDateTime($workingTime, 'long');
                break;
        }
    }

    /**
     * @param int $number
     *
     * @return string
     */
    public static function number_format($number)
    {
        return number_format($number, 2, '.', ',');
    }

    /**
     * @param string $format
     * @param string $number
     *
     * @return string
     */
    public static function money_format($format, $number)
    {
        if (function_exists('money_format')) {
            $result = money_format($format, $number);
        } else {
            $result = sprintf('%01.2f', $number);
        }

        return $result;
    }

    /**
     * Sort array values according to current locale rules, maintaining index association
     *
     * @param array $array to sort
     * @return void
     */
    public static function asort(&$array)
    {
        //if (class_exists('\Collator')) {
        //    $col = new \Collator(self::getLocale());
        //    $col->asort($array);
        //} else {
        //    asort($array);
        //}
        uasort($array, '\Patchwork\Utf8::strcasecmp');
    }
}

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS localization abstract
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

abstract class Xoops_Locale_Abstract
{
    /**
     * @return bool
     */
    public static function isMultiByte()
    {
        return false;
    }

    /**
     * @return bool
     */
    public static function isRtl()
    {
        return false;
    }

    /**
     * todo, do not forget to set this on locale load
     */
    public static function setLocale()
    {
        return setlocale(LC_ALL, self::getLocale());
    }

    /**
     * @return string
     */
    public static function getCharset()
    {
        return 'UTF-8';
    }

    /**
     * @return string
     */
    public static function getLocale()
    {
        return 'en_US';
    }

    /**
     * @return string
     */
    public static function getLangCode()
    {
        return 'en-US';
    }

    /**
     * @return string
     */
    public static function getLegacyLanguage()
    {
        return 'english';
    }

    /**
     * @return string
     */
    public static function getTimezone()
    {
        return 'Europe/London';
    }

    /**
     * @return string[]
     */
    public static function getFonts()
    {
        return array(
            'Arial',
            'Courier',
            'Georgia',
            'Helvetica',
            'Impact',
            'Verdana',
            'Haettenschweiler'
        );
    }

    /**
     * @return array
     */
    public static function getFontSizes()
    {
        return array(
            'xx-small' => 'xx-Small',
            'x-small'  => 'x-Small',
            'small'    => 'Small',
            'medium'   => 'Medium',
            'large'    => 'Large',
            'x-large'  => 'x-Large',
            'xx-large' => 'xx-Large'
        );
    }

    /**
     * @return string[]
     */
    public static function getAdminRssUrls()
    {
        return array('http://www.xoops.org/backend.php');
    }

    /**
     * !!IMPORTANT!! insert "\" before any char among reserved chars: "a = "A = "B = "c = "d = "D = "F = "g = "G = "h = "H = "i = "I = "j = "l = "L = "m = "M = "n = "O = "r = "s = "S = "t = "T = "U = "w = "W = "Y = "y = "z = "Z"
     * insert double "\" before these characters: e, f, n, r, t, v, \, ", $
     */
    public static function getFormatToday()
    {
        return "\T\o\d\a\y G:i";
    }

    /**
     * @return string
     */
    public static function getFormatYesterday()
    {
        return "\Y\\e\s\\t\\e\\r\d\a\y G:i";
    }

    /**
     * @return string
     */
    public static function getFormatMonthDay()
    {
        return "n/j G:i";
    }

    /**
     * @return string
     */
    public static function getFormatYearMonthDay()
    {
        return "Y/n/j G:i";
    }

    /**
     * @return string
     */
    public static function getFormatLongDate()
    {
        return "Y/n/j G:i:s";
    }

    /**
     * @return string
     */
    public static function getFormatMediumDate()
    {
        return "Y/n/j G:i";
    }

    /**
     * @return string
     */
    public static function getFormatShortDate()
    {
        return "Y/n/j";
    }

    /**
     * @param mixed   $str
     * @param integer $start
     * @param integer $length
     * @param string  $trimmarker
     *
     * @return string
     */
    public static function substr($str, $start, $length, $trimmarker = '...')
    {
        if (!self::isMultiByte()) {
            return (strlen($str) - $start <= $length)
                ? substr($str, $start, $length)
                : substr($str, $start, $length - strlen($trimmarker)) . $trimmarker;
        }
        if (function_exists('mb_internal_encoding') && @mb_internal_encoding(self::getCharset())) {
            $str2 = mb_strcut($str, $start, $length - strlen($trimmarker));

            return $str2 . (mb_strlen($str) != mb_strlen($str2) ? $trimmarker : '');
        }

        return $str;
    }

    /**
     * Each local language should define its own equivalent utf8_encode
     *
     * @param mixed $text
     *
     * @return string
     */
    public static function utf8_encode($text)
    {
        if (self::isMultiByte()) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($text, 'UTF-8', 'auto');
            }
        }

        return utf8_encode($text);
    }

    /**
     * @param mixed  $text
     * @param string $to
     * @param string $from
     *
     * @return string
     */
    public static function convert_encoding($text, $to = 'utf-8', $from = '')
    {
        $xoops = Xoops::getInstance();
        $xlanguage = $xoops->registry()->get('XLANGUAGE', array());
        $charset = false;
        if (isset($xlanguage['charset_base'])) {
            $charset = $xlanguage['charset_base'];
        }
        if (empty($text)) {
            return $text;
        }
        if (empty($from)) {
            $from = $charset ? $charset : XoopsLocale::getCharset();
        }
        if (empty($to) || !strcasecmp($to, $from)) {
            return $text;
        }

        if (self::isMultiByte() && function_exists('mb_convert_encoding')) {
            $converted_text = @mb_convert_encoding($text, $to, $from);
        } elseif (function_exists('iconv')) {
            $converted_text = @iconv($from, $to . "//TRANSLIT", $text);
        } elseif ('utf-8' == $to) {
            $converted_text = utf8_encode($text);
        }
        $text = empty($converted_text) ? $text : $converted_text;

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
        $ret = trim($text);

        return $ret;
    }

    /**
     * Function to display formatted times in user timezone
     * Setting $timeoffset to null (by default) will skip timezone calculation for user, using default timezone instead, which is a MUST for cached contents
     *
     * @param        $time
     * @param string $format
     * @param string $timeoffset
     *
     * @return string
     */
    public static function formatTimestamp($time, $format = 'l', $timeoffset = null)
    {
        $xoops = Xoops::getInstance();
        $format_copy = $format;
        $format = strtolower($format);

        if ($format == 'rss' || $format == 'r') {
            $TIME_ZONE = '';
            if ($xoops->getConfig('server_TZ')) {
                $server_TZ = abs(intval($xoops->getConfig('server_TZ') * 3600.0));
                $prefix = ($xoops->getConfig('server_TZ') < 0) ? ' -' : ' +';
                $TIME_ZONE = $prefix . date('Hi', $server_TZ);
            }
            $date = gmdate('D, d M Y H:i:s', intval($time)) . $TIME_ZONE;

            return $date;
        }

        if (($format == 'elapse' || $format == 'e') && $time < time()) {
            $elapse = time() - $time;
            if ($days = floor($elapse / (24 * 3600))) {
                $num = $days > 1 ? sprintf(XoopsLocale::LF_AGO_DAYS, $days) : XoopsLocale::LF_AGO_ONE_DAY;
            } elseif ($hours = floor(($elapse % (24 * 3600)) / 3600)) {
                $num = $hours > 1 ? sprintf(XoopsLocale::LF_AGO_HOURS, $hours) : XoopsLocale::LF_AGO_ONE_HOUR;
            } elseif ($minutes = floor(($elapse % 3600) / 60)) {
                $num = $minutes > 1 ? sprintf(XoopsLocale::LF_AGO_MINUTES, $minutes) : XoopsLocale::LF_AGO_ONE_MINUTE;
            } else {
                $seconds = $elapse % 60;
                $num = $seconds > 1 ? sprintf(XoopsLocale::LF_AGO_SECONDS, $seconds) : sprintf(XoopsLocale::LF_AGO_ONE_SECOND);
            }

            return $num;
        }
        // disable user timezone calculation and use default timezone,
        // for cache consideration
        if ($timeoffset === null) {
            $timeoffset = ($xoops->getConfig('default_TZ') == '') ? '0.0' : $xoops->getConfig('default_TZ');
        }
        $usertimestamp = $xoops->getUserTimestamp($time, $timeoffset);
        switch ($format) {
            case 's':
                $datestring = self::getFormatShortDate();
                break;

            case 'm':
                $datestring = self::getFormatMediumDate();
                break;

            case 'mysql':
                $datestring = 'Y-m-d H:i:s';
                break;

            case 'l':
                $datestring = self::getFormatLongDate();
                break;

            case 'c':
            case 'custom':
                static $current_timestamp, $today_timestamp, $monthy_timestamp;
                if (!isset($current_timestamp)) {
                    $current_timestamp = $xoops->getUserTimestamp(time(), $timeoffset);
                }
                if (!isset($today_timestamp)) {
                    $today_timestamp = mktime(0, 0, 0, gmdate('m', $current_timestamp), gmdate('d', $current_timestamp), gmdate('Y', $current_timestamp));
                }

                if (abs($elapse_today = $usertimestamp - $today_timestamp) < 24 * 60 * 60) {
                    $datestring = ($elapse_today > 0) ? XoopsLocale::getFormatToday() : XoopsLocale::getFormatYesterday();
                } else {
                    if (!isset($monthy_timestamp)) {
                        $monthy_timestamp[0] = mktime(0, 0, 0, 0, 0, gmdate('Y', $current_timestamp));
                        $monthy_timestamp[1] = mktime(0, 0, 0, 0, 0, gmdate('Y', $current_timestamp) + 1);
                    }
                    if ($usertimestamp >= $monthy_timestamp[0] && $usertimestamp < $monthy_timestamp[1]) {
                        $datestring = self::getFormatMonthDay();
                    } else {
                        $datestring = self::getFormatYearMonthDay();
                    }
                }
                break;

            default:
                if ($format != '') {
                    $datestring = $format_copy;
                } else {
                    $datestring = self::getFormatLongDate();
                }
                break;
        }

        return ucfirst(gmdate($datestring, $usertimestamp));
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
}

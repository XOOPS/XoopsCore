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
 * Xoops\Core\Locale\Time - localized time handling
 *
 * @category  Smarty_modifier
 * @package   modifier.datetime.php
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */

/**
 * Smarty plugin to format a DateTime value
 *
 * Examples: {$datevariable|datetime}           - Oct 19, 2015, 9:43:12 PM ('medium' is default)
 *           {$datevariable|datetime:'elapse'}  - 2 days ago
 *           {$datevariable|datetime:'Y'}       - 2015
 *
 * It is recommended to quote the format parameter
 *
 * @param \DateTime|int $datetime DateTime object, or unix timestamp
 *
 * @param string $format format as defined in Locale::formatTimestamp(). Valid formats include:
 *                        short, medium, long, full, custom, elapse,
 *                        short-date, medium-date, long-date, full-date,
 *                        short-time, medium-time, long-time, full-time,
 *                        rss, and PHP date() format string
 *
 * @return string formatted representation of $datetime
 */
function smarty_modifier_datetime($datetime, $format = 'medium')
{
    $string = \XoopsLocale::formatTimestamp($datetime, $format);
    return $string;
}

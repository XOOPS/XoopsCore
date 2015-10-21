<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Lists;

/**
 * Time - provide localized list of times at specified interval
 *
 * @category  Xoops\Core\Lists\Time
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Time extends ListAbstract
{
    /**
     * Get a localized list of times at specified interval
     *
     * @param int $interval interval between times in minutes
     * @param int $start    time in seconds from midnight to start list
     * @param int $end      time in seconds from midnight to end list
     *
     * @return array
     */
    public static function getList($interval = 15, $start = 0, $end = 86400)
    {
        $timeList = array();
        $tz = new \DateTimeZone('UTC');

        $start = (int) $start;
        $end = (int) $end;
        if (abs($end-$start) > 86400) {
            $start = 0;
            $end = 86400;
        }
        $end = ($end <= 86400 && $end > 0) ?  $end : 86400;

        $interval = ((int) $interval !== 0) ? 60 * $interval : 60*15;

        for ($t = $start; $t < $end; $t += $interval) {
            //$formatted = Calendar::formatTimeEx($t, 'short', $tz);
            $formatted = \Xoops\Core\Locale\Time::formatTime($t, 'short', $tz);
            $timeList[$formatted] = $formatted;
        }

        return $timeList;
    }
}

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

use Xoops\Core\Locale\Punic\Calendar;
use Punic\Territory;

/**
 * TimeZone - provide list of timezone names
 *
 * @category  Xoops\Core\Lists\TimeZone
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015-2018 XOOPS Project (https://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class TimeZone extends ListAbstract
{
    /**
     * Get a list of localized timezone names
     *
     * @return array
     */
    public static function getList()
    {
        $xoops = \Xoops::getInstance();
        $locale = \Xoops\Locale::getCurrent();
        $key = implode('/', ['system', 'lists', 'timezone', $locale]);
        //$xoops->cache()->delete($key);
        $timeZones = $xoops->cache()->cacheRead(
            $key,
            function () {
                $timeZones = array();
                $territories = Territory::getContinentsAndCountries();
                $maxLen = 0;
                $utcDtz = new \DateTimeZone('UTC');
                foreach ($territories as $byContinent) {
                    //$continent = $byContinent['name'];
                    foreach ($byContinent['children'] as $cCode => $cName) {
                        $allZones = $utcDtz->listIdentifiers(\DateTimeZone::PER_COUNTRY, $cCode);
                        foreach ($allZones as $zone) {
                            $maxLen = max(strlen($zone), $maxLen);
                            $name = Calendar::getTimezoneExemplarCity($zone);
                            if (!isset($timeZones[$zone]) && !empty($name)) {
                                $timeZones[$zone] = $cName['name'] . '/' . $name;
                            }
                        }
                    }
                }
                \XoopsLocale::asort($timeZones);
                $default = array(
                    'UTC' => Calendar::getTimezoneNameNoLocationSpecific(new \DateTimeZone('GMT')),
                );
                $timeZones = array_merge($default, $timeZones);
                return $timeZones;
            }
        );

        return $timeZones;
    }
}

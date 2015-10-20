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

/**
 * Month - provide list of month names
 *
 * @category  Xoops\Core\Lists\Month
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Month extends ListAbstract
{
    /**
     * Get a list of localized month names
     *
     * @param string $width The format name; it can be 'wide' (eg 'January'),
     *                      'abbreviated' (eg 'Jan') or 'narrow' (eg 'J').
     *
     * @return array
     */
    public static function getList($width = 'wide')
    {
        $months = array();
        for ($month = 1; $month <= 12; ++$month) {
            $months[$month] = Calendar::getMonthName($month, $width);
        }
        return $months;
    }
}

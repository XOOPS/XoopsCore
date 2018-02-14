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
 * SubSet - return a list which is a subset of another list
 *
 * @category  Xoops\Core\Lists\SubSet
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SubSet extends ListAbstract
{
    /**
     * return a subset of a list
     *
     * @param array $list associative list array
     * @param array $keys indexed array of keys to keep
     *
     * @return array
     */
    public static function getList($list = [], $keys = null)
    {
        if (is_array($keys)) {
            $keys = array_flip($keys);
            $subset = array_intersect_key($list, $keys);
            return $subset;
        }
        return($list);
    }
}

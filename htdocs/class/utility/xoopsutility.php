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
 * XOOPS Utilities
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      utility
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

class XoopsUtility
{

    /**
     * @static
     * @param mixed $handler
     * @param mixed $data
     * @return array|mixed
     */
    static function recursive($handler, $data)
    {
        if (is_array($data)) {
            $return = array_map(array('XoopsUtility', 'recursive'),
				array_fill(0, count($data), $handler), $data);
            return $return;
        }
        // single function
        if (is_string($handler)) {
            return function_exists($handler) ? $handler($data) : $data;
        }
        // Method of a class
        if (is_callable($handler)) {
            return call_user_func($handler, $data);
        }
        return $data;
    }
}

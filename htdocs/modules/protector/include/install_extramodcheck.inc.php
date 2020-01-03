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
 * Protector
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 * @param mixed $xoops_root_path
 * @param mixed $mytrustdirname
 */

/**
 * @param $xoops_root_path
 * @param $mytrustdirname
 *
 * @return string[]
 */
function get_writeoks_from_protector($xoops_root_path, $mytrustdirname)
{
    return [dirname(__DIR__) . '/configs'];
}

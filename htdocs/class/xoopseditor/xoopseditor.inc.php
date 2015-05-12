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
 * XOOPS Editor usage guide
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopseditor
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

if (!function_exists('xoopseditor_get_rootpath')) {
    /**
     * @return string
     */
    function xoopseditor_get_rootpath()
    {
        return \XoopsBaseConfig::get('root-path') . '/class/xoopseditor';
    }
}

$mainfile = dirname(dirname(__DIR__)) . '/mainfile.php';
require $mainfile;

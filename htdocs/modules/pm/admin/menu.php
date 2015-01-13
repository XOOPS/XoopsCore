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
 * Private message
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         pm
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

$adminmenu = array();

$i = 1;
$adminmenu[$i]['title'] = _PM_MI_INDEX;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = 'home.png';
$i++;
$adminmenu[$i]['title'] = _PM_MI_PRUNE;
$adminmenu[$i]['link'] = "admin/prune.php";
$adminmenu[$i]['icon'] = 'prune.png';
$i++;
$adminmenu[$i]['title'] = _PM_MI_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';
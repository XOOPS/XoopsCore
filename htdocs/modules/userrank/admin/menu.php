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
 * User Rank module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         userrank
 * @since           2.6.0
 * @author          Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */

$pathIcon32 = '../../media/xoops/images/icons/32/';

$adminmenu = array();

$i = 1;
$adminmenu[$i]['title'] = _USERRANK_MI_INDEX;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = 'home.png';
$i++;
$adminmenu[$i]['title'] = _USERRANK_MI_USERRANK;
$adminmenu[$i]['link'] = "admin/userrank.php";
$adminmenu[$i]['icon'] = 'user-icon.png';
$i++;
$adminmenu[$i]['title'] = _USERRANK_MI_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';
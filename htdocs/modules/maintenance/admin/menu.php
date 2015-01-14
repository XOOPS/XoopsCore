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
 * maintenance extensions
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         maintenance
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage), Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */

$adminmenu = array();
$i = 1;
$adminmenu[$i]['title'] = _MI_MAINTENANCE_INDEX;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = 'home.png';
$i++;
$adminmenu[$i]['title'] = _MI_MAINTENANCE_CENTER;
$adminmenu[$i]['link'] = "admin/center.php";
$adminmenu[$i]['icon'] = 'maintenance.png';
$i++;
$adminmenu[$i]['title'] = _MI_MAINTENANCE_DUMP;
$adminmenu[$i]['link'] = "admin/dump.php";
$adminmenu[$i]['icon'] = 'dump.png';
$i++;
$adminmenu[$i]['title'] = _MI_MAINTENANCE_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';
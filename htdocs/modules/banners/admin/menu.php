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
 * banners module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id: $
 */

$adminmenu = array();
$i = 1;
$adminmenu[$i]['title'] = _MI_BANNERS_INDEX;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = 'home.png';
++$i;
$adminmenu[$i]['title'] = _MI_BANNERS_CLIENTS;
$adminmenu[$i]['link'] = "admin/clients.php";
$adminmenu[$i]['icon'] = 'index.png';
++$i;
$adminmenu[$i]['title'] = _MI_BANNERS_BANNERS;
$adminmenu[$i]['link'] = "admin/banners.php";
$adminmenu[$i]['icon'] = 'banners.png';
++$i;
$adminmenu[$i]['title'] = _MI_BANNERS_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';

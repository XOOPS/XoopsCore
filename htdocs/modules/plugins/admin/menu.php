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
 * plugins module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         plugins
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
$adminmenu = [];
$i = 1;
$adminmenu[$i]['title'] = XoopsLocale::HOME;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['icon'] = 'home.png';
++$i;
$adminmenu[$i]['title'] = PluginsLocale::PLUGINS_MANAGER;
$adminmenu[$i]['link'] = 'admin/plugins.php';
$adminmenu[$i]['icon'] = 'synchronized.png';
++$i;
$adminmenu[$i]['title'] = XoopsLocale::ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = 'about.png';

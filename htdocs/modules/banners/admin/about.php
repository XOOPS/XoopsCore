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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id: $
 */
include dirname(__FILE__) . '/header.php';
$xoops = Xoops::getInstance();
$xoops->header();
$aboutAdmin = new XoopsModuleAdmin();
$aboutAdmin->displayNavigation('about.php');
$aboutAdmin->displayAbout('6KJ7RW5DR3VTJ', true);
$xoops->footer();
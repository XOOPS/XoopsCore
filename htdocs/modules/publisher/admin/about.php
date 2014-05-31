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
 * Publisher
 *
 * @copyright    The XOOPS Project (http://www.xoops.org)
 * @license      GNU GPL (http://www.gnu.org/licenses/gpl-2.0.html/)
 * @package      Publisher
 * @since        1.0
 * @author       Mage, Mamba
 * @version      $Id$
 */

include dirname(__FILE__) . '/admin_header.php';
$xoops = Xoops::getInstance();
$xoops->header();

$aboutAdmin = new \Xoops\Module\Admin();
$aboutAdmin->displayNavigation('about.php');
$aboutAdmin->displayAbout(false);

$xoops->footer();

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
 * @copyright   2000-2020 XOOPS Project (https://xoops.org)
 * @license      GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      Publisher
 * @since        1.0
 * @author       Mage, Mamba
 * @version      $Id$
 */
use Xoops\Module\Admin;

require_once __DIR__ . '/admin_header.php';

$xoops = Xoops::getInstance();
$xoops->header();

$adminObject = new Admin();
$adminObject->displayNavigation('index.php');

$adminObject->addConfigBoxLine('thumbnail', 'service');
$adminObject->displayIndex();

$xoops->footer();

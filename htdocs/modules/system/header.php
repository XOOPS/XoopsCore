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
 * System Header
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     system
 * @version     $Id$
 */

// Include XOOPS control panel header
include_once dirname(dirname(__DIR__)) . '/include/cp_header.php';

$xoops = Xoops::getInstance();

XoopsLoad::load('system', 'system');
XoopsLoad::load('module', 'system');
XoopsLoad::load('extension', 'system');

$system = System::getInstance();

// Check user rights
if (!$system->checkRight()) {
    $xoops->redirect(\XoopsBaseConfig::get('url'), 3, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

// System Class
//include_once $xoops->path('/modules/system/class/cookie.php');
// Load Language
$xoops->loadLocale('system');
// Include System files
include_once $xoops->path('/modules/system/include/functions.php');
// include system category definitions
include_once $xoops->path('/modules/system/constants.php');
// Get request variable
$fct = $system->cleanVars($_REQUEST, 'fct', '', 'string');

XoopsLoad::load('systembreadcrumb', 'system');

$system_breadcrumb = SystemBreadcrumb::getInstance($fct);
$system_breadcrumb->addLink(SystemLocale::CONTROL_PANEL, \XoopsBaseConfig::get('url') . '/admin.php', true);

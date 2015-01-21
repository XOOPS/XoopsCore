<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Request;

/**
 * images module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

XoopsLoad::load('system', 'system');

$xoops = Xoops::getInstance();
$system = System::getInstance();
$helper = Xoops\Module\Helper::getHelper('images');

// Get Action type
$op = Request::getCmd('op', 'list');
$start = Request::getInt('start', 0);
$imgcat_id = Request::getInt('imgcat_id', 0);

$redirect = basename($xoops->getenv('SCRIPT_NAME'));
if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$gperm_handler = $xoops->getHandlerGroupperm();
$groups = $xoops->isUser() ? $xoops->user->getGroups() : XOOPS_GROUP_ANONYMOUS;

// check WRITE right by category before continue
if (isset($imgcat_id) && ($op == 'addfile' || $op == 'editcat' || $op == 'updatecat' || $op == 'delcatok' || $op == 'delcat')) {
    $imgcat_write = $gperm_handler->checkRight('imgcat_write', $imgcat_id, $groups, $xoops->module->mid());
    if (!$imgcat_write) {
        $xoops->redirect($redirect, 1);
    }
}

// Only website administator can delete categories or images
if (!in_array(XOOPS_GROUP_ADMIN, $groups) && ($op == 'delfile' || $op == 'delfileok' || $op == 'delcatok' || $op == 'delcat')) {
    $xoops->redirect($redirect, 1);
}

// check READ right by category before continue
if (isset($imgcat_id) && $op == 'list') {
    $imgcat_read = $gperm_handler->checkRight('imgcat_read', $imgcat_id, $groups, $xoops->module->mid());
    $imgcat_write = $gperm_handler->checkRight('imgcat_write', $imgcat_id, $groups, $xoops->module->mid());
    if (!$imgcat_read && !$imgcat_write) {
        $xoops->redirect('images.php', 1);
    }
}

// Add Script
$xoops->theme()->addScript('media/xoops/xoops.js');
$xoops->theme()->addScript('modules/system/js/admin.js');
// Add Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
$xoops->theme()->addStylesheet('modules/images/css/admin.css');

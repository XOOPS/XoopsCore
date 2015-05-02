<?php
/**
 * XOOPS control panel header
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         include
 * @since           2.0.0
 * @version         $Id$
 */

/**
 * module files can include this file for admin authorization
 * the file that will include this file must be located under
 * xoops_url/modules/module_directory_name/admin_directory_name/
 */

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops_url = \XoopsBaseConfig::get('url');
include_once $xoops->path('include/cp_functions.php');

$moduleperm_handler = $xoops->getHandlerGroupperm();
if ($xoops->isUser()) {
    $url_arr = explode('/', strstr($_SERVER['REQUEST_URI'], '/modules/'));
    if (!$xoops->isActiveModule($url_arr[2])) {
        $xoops->redirect($xoops_url, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
    }
    $xoops->module = $xoops->getModuleByDirname($url_arr[2]);
    unset($url_arr);
    if (!$moduleperm_handler->checkRight('module_admin', $xoops->module->getVar('mid'), $xoops->user->getGroups())) {
        $xoops->redirect($xoops_url, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
    }
} else {
    $xoops->redirect($xoops_url . '/user.php', 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

// set config values for this module
if ($xoops->module->getVar('hasconfig') == 1 || $xoops->module->getVar('hascomments') == 1) {
    $xoops->moduleConfig = $xoops->getModuleConfigs();
}

// include the default language file for the admin interface
$xoops->loadLanguage('admin', $xoops->module->getVar('dirname'));
$xoops->moduleDirname = $xoops->module->getVar('dirname');
$xoops->isAdminSide = true;

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
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.6.0
 * @author          TXMod Xoops (AKA Timgno)
 * @version         $Id: header.php 10665 2012-12-27 10:14:15Z timgno $
 */
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
// Get main instance
XoopsLoad::load('system', 'system');
$system = System::getInstance();
//
$helper = Xoops\Module\Helper::getHelper('tdmcreate');
$xoops = $helper->xoops();
// Load local libraries
XoopsLoad::loadFile($xoops->path(dirname(__DIR__) . '/include/common.php'));
XoopsLoad::loadFile($xoops->path(dirname(__DIR__) . '/include/functions.php'));
// Get handler
$modulesHandler = $helper->getModulesHandler();
$tablesHandler = $helper->getTablesHandler();
$fieldsHandler = $helper->getFieldsHandler();
$localeHandler = $helper->getLocalesHandler();
$importHandler = $helper->getImportsHandler();
// Get $_POST, $_GET, $_REQUEST
$op = Request::getCmd('op');
$start = Request::getInt('start', 0);
// Parameters
$limit = $helper->getConfig('adminpager');
// Add Script
$xoops->theme()->addScript('media/xoops/xoops.js');
$xoops->theme()->addScript('modules/system/js/admin.js');
$xoops->theme()->addScript('modules/tdmcreate/assets/js/functions.js');
// Add Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
$xoops->theme()->addStylesheet('modules/tdmcreate/assets/css/admin.css');
// Get admin menu istance
$adminMenu = new \Xoops\Module\Admin();
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
 * page module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/include/cp_header.php';

// Get main instance
XoopsLoad::load('system', 'system');
$system = System::getInstance();

$request = Xoops_Request::getInstance();
$helper = Page::getInstance();
$xoops = $helper->xoops();

// Get handler
$content_Handler = $helper->getContentHandler();
$related_Handler = $helper->getRelatedHandler();
$link_Handler = $helper->getLinkHandler();
$rating_Handler = $helper->getRatingHandler();
$gperm_Handler = $helper->getGrouppermHandler();

// Get $_POST, $_GET, $_REQUEST
$op = $request->asStr('op', 'list');
$start = $request->asInt('start', 0);

// Parameters
$nb_limit = $helper->getConfig('page_adminpager');
$module_id = $helper->getModule()->getVar('mid');

// Define Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');

// Add Scripts
$xoops->theme()->addScript('media/xoops/xoops.js');

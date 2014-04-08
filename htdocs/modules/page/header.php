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

include '../../mainfile.php';

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

//permission
$groups = $helper->getUserGroups();
$uid = $helper->getUserId();

// Define Stylesheet
$xoops->theme()->addStylesheet($helper->url('css/styles.css'));
$xoops->theme()->addStylesheet($helper->url('css/rating.css'));

// Get $_POST, $_GET, $_REQUEST
$start = $request->asInt('start', 0);

// Parameters
$module_id = $helper->getModule()->getVar('mid');
$interval = 3600; //1 hour

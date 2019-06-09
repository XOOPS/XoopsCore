<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Request;

/**
 * page module
 *
 * @copyright       2014-2019 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package         page
 * @author          Mage Grégory (AKA Mage)
 */

include '../../mainfile.php';

// Get main instance
XoopsLoad::load('system', 'system');

$helper = Page::getInstance();
$xoops = $helper->xoops();

// Get handler
$content_Handler = $helper->getContentHandler();
$related_Handler = $helper->getRelatedHandler();
$link_Handler = $helper->getLinkHandler();
$rating_Handler = $helper->getRatingHandler();
$gperm_Handler = $helper->getGrouppermHandler();

//permission
$groups = $xoops->getUserGroups();
$uid = $helper->getUserId();

// Define Stylesheet
$xoops->theme()->addStylesheet($helper->url('css/styles.css'));
$xoops->theme()->addStylesheet($helper->url('css/rating.css'));

// Get $_POST, $_GET, $_REQUEST
$start = Request::getInt('start', 0);

// Parameters
$module_id = $helper->getModule()->getVar('mid');
$interval = 3600; //1 hour

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
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Comments
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */


include dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

$xoops = Xoops::getInstance();
XoopsLoad::load('system', 'system');

// Add Script
$xoops->theme()->addScript('media/xoops/xoops.js');
$xoops->theme()->addScript('modules/system/js/admin.js');
// Add Stylesheet
$xoops->theme()->addStylesheet('modules/system/css/admin.css');
$xoops->theme()->addStylesheet('modules/images/css/admin.css');


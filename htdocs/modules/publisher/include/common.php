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
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

use XoopsModules\Publisher\Helper;

$xoops = Xoops::getInstance();
define('PUBLISHER_DIRNAME', basename(dirname(__DIR__)));
define('PUBLISHER_URL', $xoops->url('modules/' . PUBLISHER_DIRNAME));
define('PUBLISHER_ADMIN_URL', PUBLISHER_URL . '/admin');
define('PUBLISHER_UPLOADS_URL', $xoops->url('uploads/' . PUBLISHER_DIRNAME));
define('PUBLISHER_ROOT_PATH', $xoops->path('modules/' . PUBLISHER_DIRNAME));
define('PUBLISHER_UPLOADS_PATH', $xoops->path('uploads/' . PUBLISHER_DIRNAME));

$path = dirname(__DIR__);
//XoopsLoad::addMap(array(
//    'publishermetagen'   => $path . '/class/metagen.php',
//    'publisher'          => $path . '/class/helper.php',
//    'publisherutils'     => $path . '/class/utils.php',
//    'publisherblockform' => $path . '/class/blockform.php',
//));

$helper = Helper::getInstance();
$helper->loadLanguage('common');

XoopsLoad::loadFile($helper->path('include/constants.php'));

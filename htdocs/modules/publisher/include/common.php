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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

define("PUBLISHER_DIRNAME", basename(dirname(dirname(__FILE__))));
define("PUBLISHER_URL", XOOPS_URL . '/modules/' . PUBLISHER_DIRNAME);
define("PUBLISHER_ADMIN_URL", PUBLISHER_URL . '/admin');
define("PUBLISHER_UPLOADS_URL", XOOPS_URL . '/uploads/' . PUBLISHER_DIRNAME);
define("PUBLISHER_ROOT_PATH", XOOPS_ROOT_PATH . '/modules/' . PUBLISHER_DIRNAME);
define("PUBLISHER_UPLOADS_PATH", XOOPS_ROOT_PATH . '/uploads/' . PUBLISHER_DIRNAME);

$path = dirname(dirname(__FILE__));
XoopsLoad::addMap(array(
    'publishermetagen'   => $path . '/class/metagen.php',
    'publishersession'   => $path . '/class/session.php',
    'publisher'          => $path . '/class/helper.php',
    'publisherrequest'   => $path . '/class/request.php',
    'publisherutils'     => $path . '/class/utils.php',
    'publisherblockform' => $path . '/class/blockform.php',
));

$publisher = Publisher::getInstance();
$publisher->loadLanguage('common');

XoopsLoad::loadFile($publisher->path('include/constants.php'));



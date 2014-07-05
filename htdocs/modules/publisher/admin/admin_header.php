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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$xoops = Xoops::getInstance();
$publisher = Publisher::getInstance();
$publisher->loadLanguage('modinfo');

XoopsLoad::loadFile($xoops->path(dirname(__DIR__) . '/include/common.php'));
XoopsLoad::loadFile($xoops->path(XOOPS_ROOT_PATH . '/include/cp_header.php'));

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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->header();

/* @var $helper Userconfigs */
if ($xoops->isUser() && $helper = $xoops->getModuleHelper('userconfigs')) {
    $config_handler = $helper->getHandlerConfig();
    $configs = $config_handler->getConfigsByUser($xoops->user->getVar('uid'), $xoops->module->getVar('mid'));
    Xoops_Utils::dumpVar($configs);
    $url = $xoops->url('modules/userconfigs');
    echo '<a href="' . $url . '">Change your settings</a>';
} else {
    echo 'Please login and install userconfigs module';
}

Xoops_Utils::dumpFile(__FILE__ );
$xoops->footer();

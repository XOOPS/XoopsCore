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
 * @copyright 2013-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or greater (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

function xoops_module_install_notifications(&$module)
{
    $xoops = Xoops::getInstance();
    global $xoopsDB;
    $sql = "SHOW COLUMNS FROM " . $xoopsDB->prefix("xoopsnotifications");
    $result = $xoopsDB->queryF($sql);
    if ($result && ($rows = $xoopsDB->getRowsNum($result)) == 7) {
        $sql = "SELECT * FROM " . $xoopsDB->prefix("xoopsnotifications");
        $result = $xoopsDB->query($sql);
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $sql = "INSERT INTO `" . $xoopsDB->prefix("notifications") . "` (`id`, `modid`, `itemid`, `category`, `event`, `uid`, `mode`) VALUES (" . $myrow['not_id'] . ", " . $myrow['not_modid'] . ", " . $myrow['not_itemid'] . ", " . $myrow['not_category'] . ", " . $myrow['not_event'] . ", " . $myrow['not_uid'] . ", " . $myrow['not_mode'] . ")";
            $xoopsDB->queryF($sql);
        }
        //Don't drop old table for now
        //$sql = "DROP TABLE " . $xoopsDB->prefix("xoopsnotifications");
        //$xoopsDB->queryF($sql);
    }

    XoopsLoad::loadFile($xoops->path('modules/notifications/class/helper.php'));
    $helper = Notifications::getInstance();
    $plugins = \Xoops\Module\Plugin::getPlugins('notifications');

    foreach (array_keys($plugins) as $dirname) {
        $helper->insertModuleRelations($xoops->getModuleByDirname($dirname));
    }

    return true;
}

function xoops_module_pre_uninstall_notifications(&$module)
{
    $xoops = Xoops::getInstance();
    XoopsLoad::loadFile($xoops->path('modules/notifications/class/helper.php'));
    $helper = Notifications::getInstance();
    $plugins = \Xoops\Module\Plugin::getPlugins('notifications');
    foreach (array_keys($plugins) as $dirname) {
        $helper->deleteModuleRelations($xoops->getModuleByDirname($dirname));
    }

    return true;
}

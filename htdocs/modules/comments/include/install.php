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

function xoops_module_install_comments(&$module)
{
    $xoops = Xoops::getInstance();
    global $xoopsDB;
    $sql = "SHOW COLUMNS FROM " . $xoopsDB->prefix("xoopscomments");
    $result = $xoopsDB->queryF($sql);
    if ($result && ($rows = $xoopsDB->getRowsNum($result)) == 20) {
        $sql = "SELECT * FROM " . $xoopsDB->prefix("xoopscomments");
        $result = $xoopsDB->query($sql);
        while ($myrow = $xoopsDB->fetchArray($result)) {
            $sql = "INSERT INTO `" . $xoopsDB->prefix("comments") . "` (`id`, `pid`, `rootid`, `modid`, `itemid`, `icon`, `created`, `modified`, `uid`, `ip`, `title`, `text`, `sig`, `status`, `exparams`, `dohtml`, `domsiley`, `doxcode`, `doimage`, `dobr`) VALUES (" . $myrow['com_id'] . ", " . $myrow['com_pid'] . ", " . $myrow['com_rootid'] . ", " . $myrow['com_modid'] . ", " . $myrow['com_itemid'] . ", " . $myrow['com_icon'] . ", " . $myrow['com_created'] . ", " . $myrow['com_modified'] . ", " . $myrow['com_uid'] . ", " . $myrow['com_ip'] . ", " . $myrow['com_title'] . ", " . $myrow['com_text'] . ", " . $myrow['com_sig'] . ", " . $myrow['com_status'] . ", " . $myrow['com_exparams'] . ", " . $myrow['dohtml'] . ", " . $myrow['dosmiley'] . ", " . $myrow['doxcode'] . ", " . $myrow['doimage'] . ", " . $myrow['dobr'] . ")";
            $xoopsDB->queryF($sql);
        }
        //Don't drop old table for now
        //$sql = "DROP TABLE " . $xoopsDB->prefix("xoopscomments");
        //$xoopsDB->queryF($sql);
    }

    XoopsLoad::loadFile($xoops->path('modules/comments/class/helper.php'));
    $helper = Comments::getInstance();
    $plugins = \Xoops\Module\Plugin::getPlugins('comments');

    foreach (array_keys($plugins) as $dirname) {
        $helper->insertModuleRelations($xoops->getModuleByDirname($dirname));
    }

    return true;
}

function xoops_module_pre_uninstall_comments(&$module)
{
    $xoops = Xoops::getInstance();
    XoopsLoad::loadFile($xoops->path('modules/comments/class/helper.php'));
    $helper = Comments::getInstance();
    $plugins = \Xoops\Module\Plugin::getPlugins('comments');
    foreach (array_keys($plugins) as $dirname) {
        $helper->deleteModuleRelations($xoops->getModuleByDirname($dirname));
    }

    return true;
}

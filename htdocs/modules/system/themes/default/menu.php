<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/*
 * Xoops Cpanel oxygen menu
 *
 * @copyright   The XOOPS project http://sf.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     system
 * @usbpackage  GUI
 * @since       2.4
 * @author      Mamba       <mambax7@gmail.com>
 * @author      Mojtabajml  <jamali.mojtaba@gmail.com>
 * @author      Voltan      <djvoltan@gmail.com>
 * @author      BitC3R0     <BitC3R0@gmail.com>
 * @author      trabis      <lusopoemas@gmail.com>
 * @version     1.2
 * @version     $Id$
 */

$xoops = Xoops::getInstance();
$groups = array();
if (is_object($xoops->user))
    $groups = $xoops->user->getGroups();
$all_ok = false;
if (!in_array(XOOPS_GROUP_ADMIN, $groups)) {
    $sysperm_handler = $xoops->getHandlerGroupperm();
    $ok_syscats = $sysperm_handler->getItemIds('system_admin', $groups);
} else {
    $all_ok = true;
}
// include system category definitions
include_once $xoops->path('/modules/system/constants.php');

$admin_dir = $xoops->path('/modules/system/admin');
$dirlist = XoopsLists::getDirListAsArray($admin_dir);
$index = 0;
foreach ($dirlist as $file) {
    if (XoopsLoad::fileExists($admin_dir . '/' . $file . '/xoops_version.php')) {
        include $admin_dir . '/' . $file . '/xoops_version.php';
        if ($modversion['hasAdmin']) {
            if ($xoops->getModuleConfig('active_' . $file, 'system')) {
                $category = isset($modversion['category']) ? intval($modversion['category']) : 0;
                if (false != $all_ok || in_array($modversion['category'], $ok_syscats)) {
                    $adminmenu[$index]['title'] = trim($modversion['name']);
                    $adminmenu[$index]['desc'] = trim($modversion['description']);
                    $adminmenu[$index]['link'] = 'admin.php?fct=' . $file;
                    $adminmenu[$index]['icon'] = 'icons/' . $modversion['image'];
                }
            }
        }
        unset($modversion);
    }
    $index++;
}
unset($dirlist);

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\FixedGroups;
/**
 * System menu
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

$xoops = Xoops::getInstance();
$groups = array();
if (is_object($xoops->user)) {
    $groups = $xoops->user->getGroups();
}

$all_ok = false;
if (!in_array(FixedGroups::ADMIN, $groups)) {
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
    if (XoopsLoad::fileExists($fileinc = $admin_dir . '/' . $file . '/xoops_version.php')) {
        include $fileinc;
        unset($fileinc);
        if ($modversion['hasAdmin']) {
            if ($xoops->getModuleConfig('active_' . $file, 'system')) {
                $category = isset($modversion['category']) ? (int)($modversion['category']) : 0;
                if (false != $all_ok || in_array($modversion['category'], $ok_syscats)) {
                    $adminmenu[$index]['title'] = trim($modversion['name']);
                    $adminmenu[$index]['link'] = 'admin.php?fct=' . $file;
                    $adminmenu[$index]['image'] = $modversion['image'];
                }
            }
        }
        unset($modversion);
    }
    ++$index;
}
unset($dirlist);

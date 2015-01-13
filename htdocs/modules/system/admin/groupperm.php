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
 * Group permission check
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     system
 * @version     $Id$
 */

// Include XOOPS control panel header
include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

$xoops = Xoops::getInstance();
$xoops->loadLocale('system');

$modid = isset($_POST['modid']) ? intval($_POST['modid']) : 0;

// we dont want system module permissions to be changed here
if ($modid <= 1 || !$xoops->isUser() || !$xoops->user->isAdmin($modid)) {
    $xoops->redirect($xoops->url('index.php'), 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}
$module = $xoops->getModuleById($modid);
if (!is_object($module) || !$module->getVar('isactive')) {
    $xoops->redirect($xoops->url('admin.php'), 1, XoopsLocale::E_NO_MODULE);
}

$msg = array();

$member_handler = $xoops->getHandlerMember();
$group_list = $member_handler->getGroupList();
if (is_array($_POST['perms']) && !empty($_POST['perms'])) {
    $gperm_handler = $xoops->getHandlerGroupperm();
    foreach ($_POST['perms'] as $perm_name => $perm_data) {
        if (!$xoops->security()->check(true, false, $perm_name)) {
            continue;
        }
        if (false == $gperm_handler->deleteByModule($modid, $perm_name)) {
            $msg[] = sprintf(SystemLocale::EF_COULD_NOT_RESET_GROUP_PERMISSION_FOR_MODULE, $module->getVar('name') . '(' . $perm_name . ')');
        }
        if (!array_key_exists('groups', $perm_data)){
            $msg[] = sprintf(SystemLocale::SF_ADDED_PERMISSION_FOR_GROUP, $module->getVar('name'), $perm_name, ' /');
        }else{
            foreach ($perm_data['groups'] as $group_id => $item_ids) {
                foreach ($item_ids as $item_id => $selected) {
                    if ($selected == 1) {
                        // make sure that all parent ids are selected as well
                        if ($perm_data['parents'][$item_id] != '') {
                            $parent_ids = explode(':', $perm_data['parents'][$item_id]);
                            foreach ($parent_ids as $pid) {
                                if ($pid != 0 && !in_array($pid, array_keys($item_ids))) {
                                    // one of the parent items were not selected, so skip this item
                                    $msg[] = sprintf(SystemLocale::EF_COULD_NOT_ADD_PERMISSION_FOR_GROUP, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>') . ' (' . XoopsLocale::E_ALL_PARENT_ITEMS_MUST_BE_SELECTED . ')';
                                    continue 2;
                                }
                            }
                        }
                        $gperm = $gperm_handler->create();
                        $gperm->setVar('gperm_groupid', $group_id);
                        $gperm->setVar('gperm_name', $perm_name);
                        $gperm->setVar('gperm_modid', $modid);
                        $gperm->setVar('gperm_itemid', $item_id);
                        if (!$gperm_handler->insert($gperm)) {
                            $msg[] = sprintf(SystemLocale::EF_COULD_NOT_ADD_PERMISSION_FOR_GROUP, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>');
                        } else {
                            $msg[] = sprintf(SystemLocale::SF_ADDED_PERMISSION_FOR_GROUP, '<strong>' . $perm_name . '</strong>', '<strong>' . $perm_data['itemname'][$item_id] . '</strong>', '<strong>' . $group_list[$group_id] . '</strong>');
                        }
                        unset($gperm);
                    }
                }
            }
        }
    }
}
$backlink = $xoops->getEnv("HTTP_REFERER");
if ($module->getVar('hasadmin')) {
    $adminindex = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : $module->getInfo('adminindex');
    if ($adminindex) {
        $backlink = $xoops->url('modules/' . $module->getVar('dirname') . '/' . $adminindex);
    }
}
$backlink = ($backlink) ? $backlink : XOOPS_URL . '/admin.php';

$xoops->redirect($backlink, 2, implode("<br />", $msg));

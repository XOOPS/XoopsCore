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
 * Users Manager
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

// Get main instance
$xoops = Xoops::getInstance();

// Check users rights
if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

//  Check is active
if (!$xoops->getModuleConfig('active_users', 'system')) {
    $xoops->redirect('admin.php', 2, XoopsLocale::E_SECTION_NOT_ACTIVE);
}

/*********************************************************/
/* Users Functions                                       */
/*********************************************************/
/**
 * @param int $uid
 * @param string $type
 * @return void
 */
function synchronize($uid, $type)
{
    $xoops = Xoops::getInstance();

    switch ($type) {
        case 'user':
            $total_posts = 0;
            /* @var $plugin SystemPluginInterface */
            $plugins = Xoops_Module_Plugin::getPlugins();
            foreach ($plugins as $plugin) {
                if ($res = $plugin->userPosts($uid)){
                    $total_posts += $res;
                }
            }

            $sql = "UPDATE " . $xoopsDB->prefix("users") . " SET posts = '" . (int) $total_posts . "' WHERE uid = '" . $uid . "'";
            if (!$xoopsDB->queryF($sql)) {
                $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::E_USER_NOT_UPDATED);
            }
            break;

        case 'all users':
            $sql = "SELECT uid FROM " . $xoopsDB->prefix("users") . "";
            if (!$result = $xoopsDB->query($sql)) {
                $xoops->redirect("admin.php?fct=users", 1,  XoopsLocale::E_USER_ID_NOT_FETCHED);
            }

            while ($data = $xoopsDB->fetchArray($result)) {
                synchronize($data['uid'], "user");
            }
            break;
    }
}
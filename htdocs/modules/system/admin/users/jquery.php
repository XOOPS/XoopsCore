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
 * @author      Maxime Cointin (AKA Kraven30)
 * @package     system
 * @version     $Id$
 */

include dirname(dirname(dirname(__FILE__))) . '/header.php';

$xoops = Xoops::getInstance();
$system = System::getInstance();

if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$xoops->disableErrorReporting();

if (isset($_REQUEST["op"])) {
    $op = $_REQUEST["op"];
} else {
    @$op = "default";
}

switch ($op) {

    // Display post
    case 'display_post':
        include_once $xoops->path('modules/system/include/functions.php');

        $uid = $system->cleanVars($_REQUEST, 'uid', 'int');
        $total_posts = 0;

        /* @var $plugin SystemPluginInterface */
        $plugins = Xoops_Module_Plugin::getPlugins();
        foreach ($plugins as $plugin) {
            if ($res = $plugin->userPosts($uid)) {
                $total_posts += $res;
            }
        }

        $sql = "UPDATE " . $xoopsDB->prefix("users") . " SET posts = '" . $total_posts . "' WHERE uid = '" . $uid . "'";
        if (!$result = $xoopsDB->queryF($sql)) {
            $xoops->redirect("admin.php?fct=users", 1, XoopsLocale::E_USER_NOT_UPDATED);
        } else {
            echo $total_posts;
        }
        break;
}
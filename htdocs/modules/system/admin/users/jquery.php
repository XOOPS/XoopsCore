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
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Maxime Cointin (AKA Kraven30)
 * @package     system
 * @version     $Id$
 */

include dirname(dirname(__DIR__)) . '/header.php';

$xoops = Xoops::getInstance();
$system = System::getInstance();

if (!$xoops->isUser() || !$xoops->isModule() || !$xoops->user->isAdmin($xoops->module->mid())) {
    exit(XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$xoops->logger()->quiet();
//$xoops->disableErrorReporting();

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
        $plugins = \Xoops\Module\Plugin::getPlugins();
        foreach ($plugins as $plugin) {
            if ($res = $plugin->userPosts($uid)) {
                $total_posts += $res;
            }
        }

        $qb = $xoops->db()->createXoopsQueryBuilder();
        $eb = $qb->expr();
        $sql = $qb->updatePrefix('users')
            ->set('posts', ':posts')
            ->where('uid = :uid')
            ->setParameter(':posts', $total_posts)
            ->setParameter(':uid', $uid);
        $row_count = $sql->execute();
        echo $row_count;
        break;
}

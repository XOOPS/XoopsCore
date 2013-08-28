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
 * Extended User Profile
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include dirname(__FILE__) . '/header.php';
$xoops = Xoops::getInstance();
$xoops->header();

if (!isset($_REQUEST['uid'])) {
    $xoops->redirect("index.php", 2, _PROFILE_AM_NOSELECTION);
}
$member_handler = $xoops->getHandlerMember();
$user = $member_handler->getUser($_REQUEST['uid']);
if (!$user || $user->isNew()) {
    $xoops->redirect("index.php", 2, _PROFILE_AM_USERDONEXIT);
}

if (in_array(XOOPS_GROUP_ADMIN, $user->getGroups())) {
    $xoops->redirect("index.php", 2, _PROFILE_AM_CANNOTDEACTIVATEWEBMASTERS);
}
$user->setVar('level', $_REQUEST['level']);
if ($member_handler->insertUser($user)) {
    if ($_REQUEST['level'] == 1) {
        $message = _PROFILE_AM_USER_ACTIVATED;
    } else {
        $message = _PROFILE_AM_USER_DEACTIVATED;
    }
} else {
    if ($_REQUEST['level'] == 1) {
        $message = _PROFILE_AM_USER_NOT_ACTIVATED;
    } else {
        $message = _PROFILE_AM_USER_NOT_DEACTIVATED;
    }
}
$xoops->redirect($xoops->url('modules/profile/userinfo.php?uid=' . $user->getVar('uid')), 3, $message);
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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

$xoops = Xoops::getInstance();
$op = 'main';

if (isset($_POST['op'])) {
    $op = trim($_POST['op']);
} else {
    if (isset($_GET['op'])) {
        $op = trim($_GET['op']);
    }
}

if ($op == 'main') {
    if (!$xoops->isUser()) {
        $xoops->header('module:profile/profile_userform.tpl');
        $xoops->tpl()->assign('lang_login', XoopsLocale::A_LOGIN);
        $xoops->tpl()->assign('lang_username', XoopsLocale::C_USERNAME);
        if (isset($_GET['xoops_redirect'])) {
            $xoops->tpl()->assign('redirect_page', htmlspecialchars(trim($_GET['xoops_redirect']), ENT_QUOTES));
        }
        if ($xoops->getConfig('usercookie')) {
            $xoops->tpl()->assign('lang_rememberme', XoopsLocale::REMEMBER_ME);
        }
        $xoops->tpl()->assign('lang_password', XoopsLocale::C_PASSWORD);
        $xoops->tpl()->assign('lang_lostpassword', XoopsLocale::Q_LOST_YOUR_PASSWORD);
        $xoops->tpl()->assign('lang_noproblem', XoopsLocale::NO_PROBLEM_ENTER_EMAIL_WE_HAVE_ON_FILE);
        $xoops->tpl()->assign('lang_youremail', XoopsLocale::C_YOUR_EMAIL);
        $xoops->tpl()->assign('lang_sendpassword', XoopsLocale::SEND_PASSWORD);
        $xoops->tpl()->assign('mailpasswd_token', $xoops->security()->createToken());
        include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
    }
    if (!empty($_GET['xoops_redirect'])) {
        $redirect = trim($_GET['xoops_redirect']);
        $isExternal = false;
        if ($pos = strpos($redirect, '://')) {
            $xoopsLocation = substr(XOOPS_URL, strpos(XOOPS_URL, '://') + 3);
            if (strcasecmp(substr($redirect, $pos + 3, strlen($xoopsLocation)), $xoopsLocation)) {
                $isExternal = true;
            }
        }
        if (!$isExternal) {
            header('Location: ' . $redirect);
            exit();
        }
    }
    header('Location: ./userinfo.php?uid=' . $xoops->user->getVar('uid'));
    exit();
}

if ($op == 'login') {
    include_once $xoops->path('include/checklogin.php');
    exit();
}

if ($op == 'logout') {
    $message = '';
    // Regenerate a new session id and destroy old session
    $xoops->getHandlerSession()->regenerate_id(true);
    $_SESSION = array();
    setcookie($xoops->getConfig('usercookie'), 0, -1, '/', XOOPS_COOKIE_DOMAIN, 0);
    setcookie($xoops->getConfig('usercookie'), 0, -1, '/');
    // clear entry from online users table
    if ($xoops->isUser()) {
        $xoops->getHandlerOnline()->destroy($xoops->user->getVar('uid'));
    }
    $message = XoopsLocale::S_YOU_ARE_NOW_LOGGED_OUT . '<br />' . XoopsLocale::S_THANK_YOU_FOR_VISITING_OUR_SITE;
    $xoops->redirect(XOOPS_URL . '/', 1, $message);
}

if ($op == 'actv') {
    $id = intval($_GET['id']);
    $actkey = trim($_GET['actkey']);
    $xoops->redirect("activate.php?op=actv&amp;id={$id}&amp;actkey={$actkey}", 1, '');
}

if ($op == 'delete') {
    $xoops->getConfigs();
    if (!$xoops->isUser() || $xoops->getConfig('self_delete') != 1) {
        $xoops->redirect(XOOPS_URL . '/', 5, XoopsLocale::E_NO_ACTION_PERMISSION);
    } else {
        $groups = $xoops->user->getGroups();
        if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
            // users in the webmasters group may not be deleted
            $xoops->redirect(XOOPS_URL . '/', 5, XoopsLocale::E_USER_IN_WEBMASTER_GROUP_CANNOT_BE_REMOVED);
        }
        $ok = !isset($_POST['ok']) ? 0 : intval($_POST['ok']);
        if ($ok != 1) {
            include __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
            $xoops->confirm(array('op' => 'delete', 'ok' => 1), 'user.php', XoopsLocale::Q_ARE_YOU_SURE_TO_DELETE_ACCOUNT . '<br/>' . XoopsLocale::THIS_WILL_REMOVE_ALL_YOUR_INFO);
            include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';
        } else {
            $del_uid = $xoops->user->getVar("uid");
            if (false != $xoops->getHandlerMember()->deleteUser($xoops->user)) {
                $xoops->getHandlerOnline()->destroy($del_uid);
                //todo, use preload here?
                if ($xoops->isActiveModule('notifications')) {
                    Notifications::getInstance()->getHandlerNotification()->unsubscribeByUser($del_uid);
                }
                $xoops->redirect(XOOPS_URL . '/', 5, XoopsLocale::S_YOUR_ACCOUNT_DELETED);
            }
            $xoops->redirect(XOOPS_URL . '/', 5, XoopsLocale::E_NO_ACTION_PERMISSION);
        }
    }
}

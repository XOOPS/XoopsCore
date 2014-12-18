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
 * XOOPS User
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package         core
 * @since           2.0.0
 * @author          Kazumi Ono <webmaster@myweb.ne.jp>
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->events()->triggerEvent('core.user.start');

$xoops->loadLanguage('user');

// from $_POST we use keys: op, ok
$clean_input = XoopsFilterInput::gather(
    'post',
    array(
        array('op','string'),
        array('ok', 'boolean', 0, false),
    ),
    'op' // require op parameter to return results
);
if (!$clean_input) {
    // no valid $_POST, use $_GET and set defaults
    // from $_GET we use keys: op, xoops_redirect, id, actkey
    $clean_input = XoopsFilterInput::gather(
        'get',
        array(
            array('op','string','main',true),
            array('xoops_redirect', 'weburl', '', true),
            array('id', 'int', 0, false),
            array('actkey', 'string', '', true),
        )
    );
}
$op = $clean_input['op'];

if ($op == 'login') {
    include_once $xoops->path('include/checklogin.php');
    exit();
}

if ($op == 'main') {
    if (!$xoops->isUser()) {
        $xoops->header('system_userform.html');
        $xoops->tpl()->assign('xoops_pagetitle', XoopsLocale::A_LOGIN);
        $xoops->theme()->addMeta(
            'meta',
            'keywords',
            XoopsLocale::USERNAME . ", " . XoopsLocale::PASSWORD . ", " . XoopsLocale::Q_LOST_YOUR_PASSWORD
        );
        $xoops->theme()->addMeta(
            'meta',
            'description',
            XoopsLocale::Q_LOST_YOUR_PASSWORD . " " . XoopsLocale::NO_PROBLEM_ENTER_EMAIL_WE_HAVE_ON_FILE
        );
        $xoops->tpl()->assign('lang_login', XoopsLocale::A_LOGIN);
        $xoops->tpl()->assign('lang_username', XoopsLocale::C_USERNAME);
        if (isset($clean_input['xoops_redirect'])) {
            $xoops->tpl()->assign('redirect_page', htmlspecialchars($clean_input['xoops_redirect'], ENT_QUOTES));
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
        $xoops->footer();
    }
    if (!empty($clean_input['xoops_redirect'])) {
        $redirect = $clean_input['xoops_redirect'];
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
    header('Location: ' . XOOPS_URL . '/userinfo.php?uid=' . $xoopsUser->getVar('uid'));
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

if ($op == 'delete') {
    $xoopsConfigUser = $xoops->getConfigs();
    if (!$xoops->isUser() || $xoopsConfigUser['self_delete'] != 1) {
        $xoops->redirect('index.php', 5, XoopsLocale::E_NO_ACTION_PERMISSION);
    } else {
        $groups = $xoops->user->getGroups();
        if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
            // users in the webmasters group may not be deleted
            $xoops->redirect('user.php', 5, XoopsLocale::E_USER_IN_WEBMASTER_GROUP_CANNOT_BE_REMOVED);
        }
        $ok = !isset($clean_input['ok']) ? 0 : $clean_input['ok'];
        if ($ok != 1) {
            $xoops->header();
            $xoops->confirm(
                array('op' => 'delete', 'ok' => 1),
                'user.php',
                XoopsLocale::Q_ARE_YOU_SURE_TO_DELETE_ACCOUNT . '<br/>' . XoopsLocale::THIS_WILL_REMOVE_ALL_YOUR_INFO
            );
            $xoops->footer();
        } else {
            $del_uid = $xoops->user->getVar("uid");
            $member_handler = $xoops->getHandlerMember();
            if (false != $member_handler->deleteUser($xoops->user)) {
                $xoops->getHandlerOnline()->destroy($del_uid);
                //todo, use preload here?
                if ($xoops->isActiveModule('notifications')) {
                    Notifications::getInstance()->getHandlerNotification()->unsubscribeByUser($del_uid);
                }
                $xoops->redirect('index.php', 5, XoopsLocale::S_YOUR_ACCOUNT_DELETED);
            }
            $xoops->redirect('index.php', 5, XoopsLocale::E_NO_ACTION_PERMISSION);
        }
    }
}

<?php
/**
 * XOOPS authentication/authorization
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         include
 * @since           2.0.0
 * @version         $Id$
 * @todo            Will be refactored
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xoops = Xoops::getInstance();

$uname = !isset($_POST['uname']) ? '' : trim($_POST['uname']);
$pass = !isset($_POST['pass']) ? '' : trim($_POST['pass']);
if ($uname == '' || $pass == '') {
    $xoops->redirect(XOOPS_URL . '/user.php', 1, XoopsLocale::E_INCORRECT_LOGIN);
    exit();
}

$member_handler = $xoops->getHandlerMember();
$myts = MyTextsanitizer::getInstance();

$xoopsAuth = Xoops_Auth_Factory::getAuthConnection($myts->addSlashes($uname));
$user = $xoopsAuth->authenticate($myts->addSlashes($uname), $myts->addSlashes($pass));

if (false != $user) {
    /* @var $user XoopsUser */
    if (0 == $user->getVar('level')) {
        $xoops->redirect(XOOPS_URL . '/index.php', 5, XoopsLocale::E_SELECTED_USER_DEACTIVATED_OR_NOT_ACTIVE);
        exit();
    }
    if ($xoops->getConfig('closesite') == 1) {
        $allowed = false;
        foreach ($user->getGroups() as $group) {
            if (in_array($group, $xoops->getConfig('closesite_okgrp')) || XOOPS_GROUP_ADMIN == $group) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) {
            $xoops->redirect(XOOPS_URL . '/index.php', 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
            exit();
        }
    }
    $user->setVar('last_login', time());
    if (!$member_handler->insertUser($user)) {
    }
    // Regenerate a new session id and destroy old session
    $xoops->getHandlerSession()->regenerate_id(true);
    $_SESSION = array();
    $_SESSION['xoopsUserId'] = $user->getVar('uid');
    $_SESSION['xoopsUserGroups'] = $user->getGroups();
    $user_theme = $user->getVar('theme');
    if (in_array($user_theme, $xoops->getConfig('theme_set_allowed'))) {
        $_SESSION['xoopsUserTheme'] = $user_theme;
    }

    // Set cookie for rememberme
    if ($xoops->getConfig('usercookie')) {
        if (!empty($_POST["rememberme"])) {
            setcookie($xoops->getConfig('usercookie'), $_SESSION['xoopsUserId'] . '-' . md5($user->getVar('pass') . XOOPS_DB_NAME . XOOPS_DB_PASS . XOOPS_DB_PREFIX), time() + 31536000, '/', XOOPS_COOKIE_DOMAIN, 0);
        } else {
            setcookie($xoops->getConfig('usercookie'), 0, -1, '/', XOOPS_COOKIE_DOMAIN, 0);
        }
    }

    if (!empty($_POST['xoops_redirect']) && !strpos($_POST['xoops_redirect'], 'register')) {
        $xoops_redirect = trim(rawurldecode($_POST['xoops_redirect']));
        $parsed = parse_url(XOOPS_URL);
        $url = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : 'http://';
        if (isset($parsed['host'])) {
            $url .= $parsed['host'];
            if (isset($parsed['port'])) {
                $url .= ':' . $parsed['port'];
            }
        } else {
            $url .= $_SERVER['HTTP_HOST'];
        }
        if (@$parsed['path']) {
            if (strncmp($parsed['path'], $xoops_redirect, strlen($parsed['path']))) {
                $url .= $parsed['path'];
            }
        }
        $url .= $xoops_redirect;
    } else {
        $url = XOOPS_URL . '/index.php';
    }

    // RMV-NOTIFY
    // Perform some maintenance of notification records
    if ($xoops->isActiveModule('notifications')) {
        Notifications::getInstance()->getHandlerNotification()->doLoginMaintenance($user->getVar('uid'));
    }

    $xoops->redirect($url, 1, sprintf(XoopsLocale::SF_THANK_YOU_FOR_LOGGING_IN, $user->getVar('uname')), false);
} else {
    if (empty($_POST['xoops_redirect'])) {
        $xoops->redirect(XOOPS_URL . '/user.php', 5, $xoopsAuth->getHtmlErrors());
    } else {
        $xoops->redirect(XOOPS_URL . '/user.php?xoops_redirect=' . urlencode(trim($_POST['xoops_redirect'])), 5, $xoopsAuth->getHtmlErrors(), false);
    }
}
exit();
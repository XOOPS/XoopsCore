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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         include
 * @since           2.0.0
 * @version         $Id$
 * @todo            Will be refactored
 */

use Xoops\Core\FixedGroups;

$xoops = Xoops::getInstance();

$xoops_url = \XoopsBaseConfig::get('url');

// from $_POST we use keys: uname, pass, rememberme, xoops_redirect
$clean_input = XoopsFilterInput::gather(
    'post',
    array(
        array('uname','string', '', true),
        array('pass','string', '', true),
        array('rememberme', 'boolean', 0, false),
        array('xoops_redirect', 'weburl', '', true),
    )
);

$uname = $clean_input['uname'];
$pass = $clean_input['pass'];
if ($uname == '' || $pass == '') {
    $xoops->redirect($xoops_url . '/user.php', 1, XoopsLocale::E_INCORRECT_LOGIN);
    exit();
}

$member_handler = $xoops->getHandlerMember();

$xoopsAuth = \Xoops\Auth\Factory::getAuthConnection($uname);
$user = $xoopsAuth->authenticate($uname, $pass);

if (false != $user) {
    /* @var $user XoopsUser */
    if (0 == $user->getVar('level')) {
        $xoops->redirect($xoops_url . '/index.php', 5, XoopsLocale::E_SELECTED_USER_DEACTIVATED_OR_NOT_ACTIVE);
        exit();
    }
    if (in_array(FixedGroups::REMOVED, $user->getGroups())) {
        $xoops->redirect($xoops_url . '/index.php', 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        exit();
    }
    if ($xoops->getConfig('closesite') == 1) {
        $allowed = false;
        foreach ($user->getGroups() as $group) {
            if (in_array($group, $xoops->getConfig('closesite_okgrp')) || FixedGroups::ADMIN == $group) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) {
            $xoops->redirect($xoops_url . '/index.php', 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
            exit();
        }
    }
    $user->setVar('last_login', time());
    if (!$member_handler->insertUser($user)) {
    }

    $xoops->session()->user()->recordUserLogin($user->getVar('uid'), $clean_input["rememberme"]);
    $user_theme = $user->getVar('theme');
    if (in_array($user_theme, $xoops->getConfig('theme_set_allowed'))) {
        $_SESSION['xoopsUserTheme'] = $user_theme;
    }

    $xoops->events()->triggerEvent('core.include.checklogin.success');

    if (!empty($clean_input['xoops_redirect']) && !strpos($clean_input['xoops_redirect'], 'register')) {
        $xoops_redirect = rawurldecode($clean_input['xoops_redirect']);
        $parsed = parse_url($xoops_url);
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
        $url = $xoops_url . '/index.php';
    }

    $xoops->redirect($url, 1, sprintf(XoopsLocale::SF_THANK_YOU_FOR_LOGGING_IN, $user->getVar('uname')), false);
} else {
    $xoops->events()->triggerEvent('core.include.checklogin.failed');
    if (empty($clean_input['xoops_redirect'])) {
        $xoops->redirect($xoops_url . '/user.php', 5, $xoopsAuth->getHtmlErrors());
    } else {
        $xoops->redirect(
            $xoops_url . '/user.php?xoops_redirect=' . urlencode($clean_input['xoops_redirect']),
            5,
            $xoopsAuth->getHtmlErrors(),
            false
        );
    }
}
exit();

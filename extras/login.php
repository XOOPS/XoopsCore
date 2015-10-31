<?php
// This script displays a login screen in a popupbox when SSL is enabled in the preferences. You should use this script only when your server supports SSL. Place this file under your SSL directory

// path to your xoops main directory
//todo, check this file
$path = '/path/to/xoops/directory';

include $path . '/mainfile.php';
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
include_once XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/user.php';
$op = (isset($_POST['op']) && $_POST['op'] == 'dologin') ? 'dologin' : 'login';

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['userpass']) ? trim($_POST['userpass']) : '';
if ($username == '' || $password == '') {
    $op = 'login';
}

echo '
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=' . XoopsLocale::getCharset() . '" />
    <meta http-equiv="content-language" content="' . XoopsLocale::getLangCode() . '" />
    <title>' . $xoopsConfig['sitename'] . '</title>
    <link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/xoops.css" />
';
$style = xoops_getcss($xoopsConfig['theme_set']);
if ($style == '') {
    $style = xoops_getcss($xoopsConfig['theme_set']);
}
if ($style != '') {
    echo '<link rel="stylesheet" type="text/css" media="all" href="' . $style . '" />';
}
echo '
  </head>
  <body>
';

if ($op == 'dologin') {
    $member_handler = xoops_gethandler('member');
    $myts = \Xoops\Core\Text\Sanitizer::getInstance();
    $user = $member_handler->loginUser($username, $password);
    if (is_object($user)) {
        if (0 == $user->getVar('level')) {
            redirect_header(XOOPS_URL . '/index.php', 5, XoopsLocale::E_SELECTED_USER_DEACTIVATED_OR_NOT_ACTIVE);
            exit();
        }
        if ($xoopsConfig['closesite'] == 1) {
            $allowed = false;
            foreach ($user->getGroups() as $group) {
                if (in_array($group, $xoopsConfig['closesite_okgrp']) || XOOPS_GROUP_ADMIN == $group) {
                    $allowed = true;
                    break;
                }
            }
            if (!$allowed) {
                redirect_header(XOOPS_URL . '/index.php', 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
                exit();
            }
        }
        $user->setVar('last_login', time());
        if (!$member_handler->insertUser($user)) {
        }
        $_SESSION = array();
        $_SESSION['xoopsUserId'] = $user->getVar('uid');
        $_SESSION['xoopsUserGroups'] = $user->getGroups();
        if (!empty($xoopsConfig['use_ssl'])) {
            xoops_confirm(array($xoopsConfig['sslpost_name'] => session_id()), XOOPS_URL . '/misc.php?action=showpopups&amp;type=ssllogin', XoopsLocale::PRESS_BUTTON_BELLOW_TO_LOGIN, XoopsLocale::A_LOGIN);
        } else {
            echo sprintf(XoopsLocale::SF_THANK_YOU_FOR_LOGGING_IN, $user->getVar('uname'));
            echo '<div style="text-align:center;"><input value="' . XoopsLocale::A_CLOSE . '" type="button" onclick="document.window.opener.location.reload();document.window.close();" /></div>';
        }
    } else {
        xoops_error(XoopsLocale::E_INCORRECT_LOGIN . '<br /><a href="login.php">' . XoopsLocale::GO_BACK . '</a>');
    }
}

if ($op == 'login') {
    echo '
    <div style="text-align: center; padding: 5; margin: 0">
    <form action="login.php" method="post">
      <table class="outer" width="95%">
        <tr>
          <td class="head">' . XoopsLocale::C_USERNAME . '</td>
          <td class="even"><input type="text" name="username" value="" /></td>
        </tr>
        <tr>
          <td class="head">' . XoopsLocale::C_PASSWORD . '</td>
          <td class="even"><input type="password" name="userpass" value="" /></td>
        </tr>
        <tr>
          <td class="head">&nbsp;</td>
          <td class="even"><input type="hidden" name="op" value="dologin" /><input type="submit" name="submit" value="' . XoopsLocale::A_LOGIN . '" /></td>
        </tr>
      </table>
    </form>
    </div>
    ';
}

echo '
  </body>
</html>
';
?>

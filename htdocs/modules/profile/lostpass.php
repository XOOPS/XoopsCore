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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : $email;

if ($email == '') {
    $xoops->redirect("user.php", 2, XoopsLocale::E_NO_USER_FOUND, false);
}

$myts = \Xoops\Core\Text\Sanitizer::getInstance();
$member_handler = $xoops->getHandlerMember();
/* @var $user XoopsUser */
list($user) = $member_handler->getUsers(new Criteria('email', $email));

if (empty($user)) {
    $msg = XoopsLocale::E_NO_USER_FOUND;
    $xoops->redirect("user.php", 2, $msg, false);
} else {
    $code = isset($_GET['code']) ? trim($_GET['code']) : '';
    $areyou = substr($user->getVar("pass"), 0, 5);
    if ($code != '' && $areyou == $code) {
        $newpass = $xoops->makePass();
        $xoopsMailer = $xoops->getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass2.tpl");
        $xoopsMailer->assign("SITENAME", $xoops->getConfig('sitename'));
        $xoopsMailer->assign("ADMINMAIL", $xoops->getConfig('adminmail'));
        $xoopsMailer->assign("SITEURL", \XoopsBaseConfig::get('url') . "/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD", $newpass);
        $xoopsMailer->setToUsers($user);
        $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
        $xoopsMailer->setFromName($xoops->getConfig('sitename'));
        $xoopsMailer->setSubject(sprintf(XoopsLocale::F_NEW_PASSWORD_REQUEST_AT, \XoopsBaseConfig::get('url')));
        if (!$xoopsMailer->send()) {
            echo $xoopsMailer->getErrors();
        }

        // todo convert to handleer update and bcrypt
        // Next step: add the new password to the database
        $sql = sprintf("UPDATE %s SET pass = '%s' WHERE uid = %u", $xoopsDB->prefix("users"), md5($newpass), $user->getVar('uid'));
        if (!$xoopsDB->queryF($sql)) {
            $xoops->header();
            echo XoopsLocale::E_USER_NOT_UPDATED;
            include __DIR__ . '/footer.php';
        }
        $xoops->redirect("user.php", 3, sprintf(XoopsLocale::SF_PASSWORD_SENT_TO, $user->getVar("uname")), false);
        // If no Code, send it
    } else {
        $xoopsMailer = $xoops->getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass1.tpl");
        $xoopsMailer->assign("SITENAME", $xoops->getConfig('sitename'));
        $xoopsMailer->assign("ADMINMAIL", $xoops->getConfig('adminmail'));
        $xoopsMailer->assign("SITEURL", \XoopsBaseConfig::get('url') . "/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD_LINK", \XoopsBaseConfig::get('url') . "/modules/profile/lostpass.php?email={$email}&code=" . $areyou);
        $xoopsMailer->setToUsers($user);
        $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
        $xoopsMailer->setFromName($xoops->getConfig('sitename'));
        $xoopsMailer->setSubject(sprintf(XoopsLocale::F_NEW_PASSWORD_REQUEST_AT, $xoops->getConfig('sitename')));
        $xoops->header();
        if (!$xoopsMailer->send()) {
            echo $xoopsMailer->getErrors();
        }
        echo "<h4>";
        printf(XoopsLocale::F_CONFIRMATION_EMAIL_SENT, $user->getVar('uname'));
        echo "</h4>";
        include __DIR__ . '/footer.php';
    }
}

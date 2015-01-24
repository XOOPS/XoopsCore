<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Request;

/**
 * XOOPS password recovery
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
$xoops = Xoops::getInstance();
$xoops->preload()->triggerEvent('core.lostpass.start');

$xoops->loadLanguage('user');

$email = Request::getEmail('email', null, 'GET');
$email = Request::getEmail('email', $email, 'POST');

if (empty($email)) {
    $xoops->redirect("user.php", 2, XoopsLocale::NO_);
    exit();
}

$userHandler = Xoops::getInstance()->getHandlerUser();
$getuser = $userHandler->getObjects(new Criteria('email', $email));

if (empty($getuser)) {
    $msg = XoopsLocale::E_NO_USER_FOUND;
    $xoops->redirect("user.php", 2, $msg);
} else {
    $userObject = $getuser[0]; // what if there was more than one?
    $code = Request::getCmd('code', '', 'GET');
    $areyou = substr(md5($userObject->getVar("pass")), 0, 5);
    if ($code != '' && $areyou == $code) {
        $newpass = $xoops->makePass();
        $xoopsMailer = $xoops->getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass2.tpl");
        $xoopsMailer->assign("SITENAME", $xoops->getConfig('sitename'));
        $xoopsMailer->assign("ADMINMAIL", $xoops->getConfig('adminmail'));
        $xoopsMailer->assign("SITEURL", XOOPS_URL . "/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD", $newpass);
        $xoopsMailer->setToUsers($userObject);
        $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
        $xoopsMailer->setFromName($xoops->getConfig('sitename'));
        $xoopsMailer->setSubject(sprintf(XoopsLocale::F_NEW_PASSWORD_REQUEST_AT, XOOPS_URL));
        if (!$xoopsMailer->send()) {
            echo $xoopsMailer->getErrors();
        }
        // Next step: add the new password to the database
        $userObject->setVar("pass", password_hash($newpass, PASSWORD_DEFAULT));
        if (false === $userHandler->insert($userObject)) {
            $xoops->header();
            echo XoopsLocale::E_USER_NOT_UPDATED;
            $xoops->footer();
        }
        $xoops->redirect("user.php", 3, sprintf(XoopsLocale::SF_PASSWORD_SENT_TO, $userObject->getVar("uname")), false);
        // If no Code, send it
    } else {
        $xoopsMailer = $xoops->getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass1.tpl");
        $xoopsMailer->assign("SITENAME", $xoops->getConfig('sitename'));
        $xoopsMailer->assign("ADMINMAIL", $xoops->getConfig('adminmail'));
        $xoopsMailer->assign("SITEURL", XOOPS_URL . "/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD_LINK", XOOPS_URL . "/lostpass.php?email=" . $email . "&code=" . $areyou);
        $xoopsMailer->setToUsers($userObject);
        $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
        $xoopsMailer->setFromName($xoops->getConfig('sitename'));
        $xoopsMailer->setSubject(sprintf(XoopsLocale::F_NEW_PASSWORD_REQUEST_AT, $xoops->getConfig('sitename')));
        $xoops->header();
        if (!$xoopsMailer->send()) {
            echo $xoopsMailer->getErrors();
        }
        echo "<h4>";
        printf(XoopsLocale::F_CONFIRMATION_EMAIL_SENT, $userObject->getVar("uname"));
        echo "</h4>";
        $xoops->footer();
    }
}

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

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

$xoops = Xoops::getInstance();
$xoops->getConfigs();

if (!$xoops->user || $xoops->getConfig('allow_chgmail') != 1) {
    $xoops->redirect(XOOPS_URL . "/modules/" . $xoops->module->getVar('dirname', 'n') . "/", 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$xoops->header('profile_email.tpl');

if (!isset($_POST['submit']) || !isset($_POST['passwd'])) {
    //show change password form
    $form = new XoopsThemeForm(_PROFILE_MA_CHANGEMAIL, 'emailform', $_SERVER['REQUEST_URI'], 'post', true);
    $form->addElement(new XoopsFormPassword(XoopsLocale::PASSWORD, 'passwd', 15, 50), true);
    $form->addElement(new XoopsFormText(_PROFILE_MA_NEWMAIL, 'newmail', 15, 50), true);
    $form->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    $form->assign($xoops->tpl());
} else {
    $myts = MyTextSanitizer::getInstance();
    $pass = @$myts->stripSlashesGPC(trim($_POST['passwd']));
    $email = @$myts->stripSlashesGPC(trim($_POST['newmail']));
    $errors = array();
    if (md5($pass) != $xoops->user->getVar('pass', 'n')) {
        $errors[] = _PROFILE_MA_WRONGPASSWORD;
    }
    if (!$xoops->checkEmail($email)) {
        $errors[] = XoopsLocale::E_INVALID_EMAIL;
    }

    if ($errors) {
        $msg = implode('<br />', $errors);
    } else {
        //update password
        $xoops->user->setVar('email', trim($_POST['newmail']));

        $member_handler = $xoops->getHandlerMember();
        if ($member_handler->insertUser($xoops->user)) {
            $msg = _PROFILE_MA_EMAILCHANGED;

            //send email to new email address
            $xoopsMailer = $xoops->getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setTemplateDir($xoops->module->getVar('dirname', 'n'));
            $xoopsMailer->setTemplate('emailchanged.tpl');
            $xoopsMailer->assign("SITENAME", $xoops->getConfig('sitename'));
            $xoopsMailer->assign("ADMINMAIL", $xoops->getConfig('adminmail'));
            $xoopsMailer->assign("SITEURL", XOOPS_URL . "/");
            $xoopsMailer->assign("NEWEMAIL", $email);
            $xoopsMailer->setToEmails($email);
            $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
            $xoopsMailer->setFromName($xoops->getConfig('sitename'));
            $xoopsMailer->setSubject(sprintf(_PROFILE_MA_NEWEMAIL, $xoops->getConfig('sitename')));
            $xoopsMailer->send();

        } else {
            $msg = implode('<br />', $xoops->user->getErrors() );
        }
    }
    $xoops->redirect(XOOPS_URL . '/modules/' . $xoops->module->getVar('dirname', 'n') . '/userinfo.php?uid=' . $xoops->user->getVar('uid'), 2, $msg);
}

$xoops->appendConfig('profile_breadcrumbs', array('title' => _PROFILE_MA_CHANGEMAIL));

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';

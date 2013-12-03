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
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';
$xoops = Xoops::getInstance();

if (!$xoops->isUser()) {
    $xoops->redirect(XOOPS_URL, 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$xoops->header('profile_changepass.html');

if (!isset($_POST['submit'])) {
    //show change password form
    $form = new XoopsThemeForm(_PROFILE_MA_CHANGEPASSWORD, 'form', $_SERVER['REQUEST_URI'], 'post', true);
    $form->addElement(new XoopsFormPassword(_PROFILE_MA_OLDPASSWORD, 'oldpass', 15, 50), true);
    $form->addElement(new XoopsFormPassword(_PROFILE_MA_NEWPASSWORD, 'newpass', 15, 50), true);
    $form->addElement(new XoopsFormPassword(XoopsLocale::VERIFY_PASSWORD, 'vpass', 15, 50), true);
    $form->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    $form->assign($xoops->tpl());
    $xoops->appendConfig('profile_breadcrumbs', array('title' => _PROFILE_MA_CHANGEPASSWORD));

} else {
    $xoops->getConfigs();
    $myts = MyTextSanitizer::getInstance();
    $oldpass = @$myts->stripSlashesGPC(trim($_POST['oldpass']));
    $password = @$myts->stripSlashesGPC(trim($_POST['newpass']));
    $vpass = @$myts->stripSlashesGPC(trim($_POST['vpass']));
    $errors = array();
    $hash = $xoops->user->getVar('pass', 'n');
    $type = substr($hash, 0, 1);
    // see if we have a crypt like signature, old md5 hash is just hex digits
    if ($type=='$') {
        if (!password_verify($oldpass, $hash)) {
                $errors[] = _PROFILE_MA_WRONGPASSWORD;
        }
    } elseif ($hash!=md5($oldpass)) {
            $errors[] = _PROFILE_MA_WRONGPASSWORD;
    }
    if (mb_strlen($password) < $xoops->getConfig('minpass')) {
        $errors[] = sprintf(XoopsLocale::EF_PASSWORD_MUST_BE_GREATER_THAN, $xoops->getConfig('minpass'));
    }
    if ($password != $vpass) {
        $errors[] = XoopsLocale::E_PASSWORDS_MUST_MATCH;
    }

    if ($errors) {
        $msg = implode('<br />', $errors);
    } else {
        //update password
        $xoops->user->setVar('pass', password_hash($password, PASSWORD_DEFAULT));
        if ($xoops->getHandlerMember()->insertUser($xoops->user)) {
            $msg = _PROFILE_MA_PASSWORDCHANGED;
        } else {
            $msg = _PROFILE_MA_ERRORDURINGSAVE;
        }
    }
    $xoops->redirect(XOOPS_URL . '/modules/' . $xoops->module->getVar('dirname', 'n') . '/userinfo.php?uid=' . $xoops->user->getVar('uid'), 2, $msg);
}

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';

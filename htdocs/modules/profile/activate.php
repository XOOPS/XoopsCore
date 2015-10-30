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
 */

include __DIR__ . '/header.php';
$xoops = Xoops::getInstance();

$xoops->header();
if (!empty($_GET['id']) && !empty($_GET['actkey'])) {
    $id = (int)($_GET['id']);
    $actkey = trim($_GET['actkey']);
    if (empty($id)) {
        $xoops->redirect($xoops->url('/'), 1, '');
        exit();
    }
    $member_handler = $xoops->getHandlerMember();
    $thisuser = $member_handler->getUser($id);
    if (!is_object($thisuser)) {
        $xoops->redirect($xoops->url('/'), 1, '');
    }
    if ($thisuser->getVar('actkey') != $actkey) {
        $xoops->redirect($xoops->url('/'), 5, XoopsLocale::E_ACTIVATION_KEY_INCORRECT);
    } else {
        if ($thisuser->getVar('level') > 0) {
            $xoops->redirect(
                $xoops->url('modules/' . $xoops->module->getVar('dirname', 'n'). '/index.php'),
                5,
                XoopsLocale::E_SELECTED_ACCOUNT_IS_ALREADY_ACTIVATED,
                false
            );
        } else {
            if (false != $member_handler->activateUser($thisuser)) {
                $xoops->getConfigs();
                if ($xoops->getConfig('activation_type') == 2) {
                    $myts = \Xoops\Core\Text\Sanitizer::getInstance();
                    $xoopsMailer = $xoops->getMailer();
                    $xoopsMailer->useMail();
                    $xoopsMailer->setTemplate('activated.tpl');
                    $xoopsMailer->assign('SITENAME', $xoops->getConfig('sitename'));
                    $xoopsMailer->assign('ADMINMAIL', $xoops->getConfig('adminmail'));
                    $xoopsMailer->assign('SITEURL', \XoopsBaseConfig::get('url') . "/");
                    $xoopsMailer->setToUsers($thisuser);
                    $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                    $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                    $xoopsMailer->setSubject(sprintf(XoopsLocale::F_YOUR_ACCOUNT_AT, $xoops->getConfig('sitename')));
                    $xoops->footer();
                    if (!$xoopsMailer->send()) {
                        printf(XoopsLocale::EF_NOTIFICATION_EMAIL_NOT_SENT_TO, $thisuser->getVar('uname'));
                    } else {
                        printf(XoopsLocale::SF_NOTIFICATION_EMAIL_SENT_TO, $thisuser->getVar('uname'));
                    }
                    include __DIR__ . '/footer.php';
                } else {
                    $xoops->redirect(
                        $xoops->url('modules/' . $xoops->module->getVar('dirname', 'n') . '/user.php'),
                        5,
                        XoopsLocale::S_YOUR_ACCOUNT_ACTIVATED . ' ' . XoopsLocale::LOGIN_WITH_REGISTERED_PASSWORD,
                        false
                    );
                }
            } else {
                $xoops->redirect($xoops->url('/'), 5, 'Activation failed!');
            }
        }
    }
// Not implemented yet: re-send activiation code
} elseif (!empty($_REQUEST['email']) && $xoops->getConfig('activation_type') != 0) {
    $myts = \Xoops\Core\Text\Sanitizer::getInstance();
    $member_handler = $xoops->getHandlerMember();
    $getuser = $member_handler->getUsers(new Criteria('email', trim($_REQUEST['email'])));
    if (count($getuser) == 0) {
        $xoops->redirect(\XoopsBaseConfig::get('url'), 2, XoopsLocale::E_NO_USER_FOUND);
    }
    /* @var XoopsUser $getuser */
    $getuser = $getuser[0];
    if ($getuser->isActive()) {
        $xoops->redirect(\XoopsBaseConfig::get('url'), 2, XoopsLocale::E_SELECTED_ACCOUNT_IS_ALREADY_ACTIVATED);
    }
    $xoopsMailer = $xoops->getMailer();
    $xoopsMailer->useMail();
    $xoopsMailer->setTemplate('register.tpl');
    $xoopsMailer->assign('SITENAME', $xoops->getConfig('sitename'));
    $xoopsMailer->assign('ADMINMAIL', $xoops->getConfig('adminmail'));
    $xoopsMailer->assign('SITEURL', \XoopsBaseConfig::get('url') . "/");
    $xoopsMailer->setToUsers($getuser[0]);
    $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
    $xoopsMailer->setFromName($xoops->getConfig('sitename'));
    $xoopsMailer->setSubject(sprintf(XoopsLocale::F_USER_ACTIVATION_KEY_FOR, $getuser->getVar('uname')));
    if (!$xoopsMailer->send()) {
        echo XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' ' . XoopsLocale::EMAIL_HAS_NOT_BEEN_SENT_WITH_ACTIVATION_KEY;
    } else {
        echo XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' ' . XoopsLocale::EMAIL_HAS_BEEN_SENT_WITH_ACTIVATION_KEY;
    }
} else {
    $form = new Xoops\Form\ThemeForm('', 'form', 'activate.php');
    $form->addElement(new Xoops\Form\Text(XoopsLocale::EMAIL, 'email', 25, 255));
    $form->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    $form->display();
}

$xoops->appendConfig('profile_breadcrumbs', array('caption' => _PROFILE_MA_REGISTER));
include __DIR__ . '/footer.php';

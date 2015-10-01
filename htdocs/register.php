<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\FixedGroups;
use Xoops\Core\Kernel\Handlers\XoopsUser;

/**
 * XOOPS Register
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @author          Kazumi Ono <webmaster@myweb.ne.jp>
 * @version         $Id$
 */
include __DIR__ . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops_url = \XoopsBaseConfig::get('url');
$xoops->events()->triggerEvent('core.register.start');
$xoops->loadLanguage('user');

$myts = MyTextSanitizer::getInstance();

$xoopsConfigUser = $xoops->getConfigs();

if (empty($xoopsConfigUser['allow_register'])) {
    $xoops->redirect('index.php', 6, XoopsLocale::E_WE_ARE_CLOSED_FOR_REGISTRATION);
}

// from $_POST we use keys: op, uname, email, url, pass, vpass, timezone_offset,
//                          user_viewemail, user_mailok, agree_disc
$clean_input = XoopsFilterInput::gather(
    'post',
    array(
        array('op','string', 'register', true),
        array('uname','string', '', true),
        array('email','string', '', true),
        array('url','weburl', '', true),
        array('pass','string', '', true),
        array('vpass','string', '', true),
        array('timezone_offset','float', $xoopsConfig['default_TZ'], false),
        array('user_viewemail','boolean', false, false),
        array('user_mailok','boolean', false, false),
        array('agree_disc','boolean', false, false),
    )
);

// from $_GET we use keys: op, id, actkey
$clean_get_input = XoopsFilterInput::gather(
    'get',
    array(
        array('op','string', 'register', true),
        array('id','int'),
        array('actkey','string', '', true),
    ),
    'actkey'
);

// move clean array to individual variables
$op=$clean_input['op'];
$uname=$clean_input['uname'];
$email=$clean_input['email'];
$url=$clean_input['url'];
$pass=$clean_input['pass'];
$vpass=$clean_input['vpass'];
$timezone_offset=$clean_input['timezone_offset'];
$user_viewemail=$clean_input['user_viewemail'];
$user_mailok=$clean_input['user_mailok'];
$agree_disc=$clean_input['agree_disc'];
// if this is an activation, use get
if ($clean_get_input!==false) {
    $op = $clean_get_input['op'];
    $id = $clean_get_input['id'];
    $actkey = $clean_get_input['actkey'];
}

switch ($op) {
    case 'newuser':
        $xoops->header();
        $xoops->tpl()->assign('xoops_pagetitle', XoopsLocale::USER_REGISTRATION);
        $stop = '';
        if (!$xoops->security()->check()) {
            $stop .= implode('<br />', $xoops->security()->getErrors()) . "<br />";
        }
        if ($xoopsConfigUser['reg_dispdsclmr'] != 0 && $xoopsConfigUser['reg_disclaimer'] != '') {
            if (empty($agree_disc)) {
                $stop .= XoopsLocale::E_YOU_HAVE_TO_AGREE_TO_DISCLAIMER . '<br />';
            }
        }
        $stop .= XoopsUserUtility::validate($uname, $email, $pass, $vpass);
        if (empty($stop)) {
            echo XoopsLocale::USERNAME . ": " . $myts->htmlSpecialChars($uname) . "<br />";
            echo XoopsLocale::EMAIL . ": " . $myts->htmlSpecialChars($email) . "<br />";
            if ($url != '') {
                $url = $xoops->formatURL($url);
                echo XoopsLocale::WEBSITE . ': ' . $myts->htmlSpecialChars($url) . '<br />';
            }
            $f_timezone = ($timezone_offset < 0) ? 'GMT ' . $timezone_offset : 'GMT +' . $timezone_offset;
            echo XoopsLocale::TIME_ZONE . ": $f_timezone<br />";
            echo "<form action='register.php' method='post'>";
            $cpatcha = new Xoops\Form\Captcha();
            echo "<br />" . $cpatcha->getCaption() . ": " . $cpatcha->render();
            echo "<input type='hidden' name='uname' value='" . $myts->htmlSpecialChars($uname) . "' />
                  <input type='hidden' name='email' value='" . $myts->htmlSpecialChars($email) . "' />
                  <input type='hidden' name='user_viewemail' value='" . $user_viewemail . "' />
                  <input type='hidden' name='timezone_offset' value='" . (float)$timezone_offset . "' />
                  <input type='hidden' name='url' value='" . $myts->htmlSpecialChars($url) . "' />
                  <input type='hidden' name='pass' value='" . $myts->htmlSpecialChars($pass) . "' />
                  <input type='hidden' name='vpass' value='" . $myts->htmlSpecialChars($vpass) . "' />
                  <input type='hidden' name='user_mailok' value='" . $user_mailok . "' />
                  <br /><br /><input type='hidden' name='op' value='finish' />"
                  . $xoops->security()->getTokenHTML()
                  . "<input type='submit' value='" . XoopsLocale::A_FINISH . "' /></form>";
        } else {
            echo "<span class='red'>$stop</span>";
            include $xoops->path('include/registerform.php');
            $reg_form->display();
        }
        $xoops->footer();
        break;

    case 'finish':
        $xoops->header();
        $stop = XoopsUserUtility::validate($uname, $email, $pass, $vpass);
        if (!$xoops->security()->check()) {
            $stop .= implode('<br />', $xoops->security()->getErrors()) . "<br />";
        }
        $xoopsCaptcha = XoopsCaptcha::getInstance();
        if (!$xoopsCaptcha->verify()) {
            $stop .= $xoopsCaptcha->getMessage() . "<br />";
        }
        if (empty($stop)) {
            $member_handler = $xoops->getHandlerMember();
            $newuser = $member_handler->createUser();
            $newuser->setVar('user_viewemail', $user_viewemail, true);
            $newuser->setVar('uname', $uname, true);
            $newuser->setVar('email', $email, true);
            if ($url != '') {
                $newuser->setVar('url', $xoops->formatURL($url), true);
            }
            $newuser->setVar('user_avatar', 'blank.gif', true);
            $actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
            $newuser->setVar('actkey', $actkey, true);
            $newuser->setVar('pass', password_hash($pass, PASSWORD_DEFAULT), true);
            $newuser->setVar('timezone_offset', $timezone_offset, true);
            $newuser->setVar('user_regdate', time(), true);
            $newuser->setVar('uorder', $xoops->getConfig('com_order'), true);
            $newuser->setVar('umode', $xoops->getConfig('com_mode'), true);
            $newuser->setVar('theme', $xoops->getConfig('theme_set'), true);
            $newuser->setVar('user_mailok', $user_mailok, true);
            if ($xoopsConfigUser['activation_type'] == 1) {
                $newuser->setVar('level', 1, true);
            } else {
                $newuser->setVar('level', 0, true);
            }
            if (!$member_handler->insertUser($newuser)) {
                echo XoopsLocale::E_USER_NOT_REGISTERED;
                $xoops->footer();
            }
            $newid = $newuser->getVar('uid');
            if (!$member_handler->addUserToGroup(FixedGroups::USERS, $newid)) {
                echo XoopsLocale::E_USER_NOT_REGISTERED;
                $xoops->footer();
            }
            if ($xoopsConfigUser['activation_type'] == 1) {
                XoopsUserUtility::sendWelcome($newuser);
                $xoops->redirect(
                    'index.php',
                    4,
                    XoopsLocale::S_YOUR_ACCOUNT_ACTIVATED . ' ' . XoopsLocale::LOGIN_WITH_REGISTERED_PASSWORD
                );
            }
            // Sending notification email to user for self activation
            if ($xoopsConfigUser['activation_type'] == 0) {
                $xoopsMailer = $xoops->getMailer();
                $xoopsMailer->useMail();
                $xoopsMailer->setTemplate('register.tpl');
                $xoopsMailer->assign('SITENAME', $xoops->getConfig('sitename'));
                $xoopsMailer->assign('ADMINMAIL', $xoops->getConfig('adminmail'));
                $xoopsMailer->assign('SITEURL', $xoops_url . "/");
                $xoopsMailer->setToUsers(new XoopsUser($newid));
                $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                $xoopsMailer->setSubject(sprintf(XoopsLocale::F_USER_ACTIVATION_KEY_FOR, $uname));
                if (!$xoopsMailer->send()) {
                    echo XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' '
                    . XoopsLocale::EMAIL_HAS_NOT_BEEN_SENT_WITH_ACTIVATION_KEY;
                } else {
                    echo XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' '
                    . XoopsLocale::EMAIL_HAS_BEEN_SENT_WITH_ACTIVATION_KEY;
                }
                // Sending notification email to administrator for activation
            } elseif ($xoopsConfigUser['activation_type'] == 2) {
                $xoopsMailer = $xoops->getMailer();
                $xoopsMailer->useMail();
                $xoopsMailer->setTemplate('adminactivate.tpl');
                $xoopsMailer->assign('USERNAME', $uname);
                $xoopsMailer->assign('USEREMAIL', $email);
                $xoopsMailer->assign(
                    'USERACTLINK',
                    $xoops_url . '/register.php?op=actv&id=' . $newid . '&actkey=' . $actkey
                );
                $xoopsMailer->assign('SITENAME', $xoops->getConfig('sitename'));
                $xoopsMailer->assign('ADMINMAIL', $xoops->getConfig('adminmail'));
                $xoopsMailer->assign('SITEURL', $xoops_url . "/");
                $member_handler = $xoops->getHandlerMember();
                $xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['activation_group']));
                $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                $xoopsMailer->setSubject(sprintf(XoopsLocale::F_USER_ACTIVATION_KEY_FOR, $uname));
                if (!$xoopsMailer->send()) {
                    echo XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' '
                        . XoopsLocale::EMAIL_HAS_NOT_BEEN_SENT_WITH_ACTIVATION_KEY;
                    echo XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' '
                        . XoopsLocale::PLEASE_WAIT_FOR_ACCOUNT_ACTIVATION;
                }
            }
            if ($xoopsConfigUser['new_user_notify'] == 1 && !empty($xoopsConfigUser['new_user_notify_group'])) {
                $xoopsMailer = $xoops->getMailer();
                $xoopsMailer->reset();
                $xoopsMailer->useMail();
                $member_handler = $xoops->getHandlerMember();
                $xoopsMailer->setToGroups($member_handler->getGroup($xoopsConfigUser['new_user_notify_group']));
                $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                $xoopsMailer->setSubject(
                    sprintf(XoopsLocale::F_NEW_USER_REGISTRATION_AT, $xoops->getConfig('sitename'))
                );
                $xoopsMailer->setBody(sprintf(XoopsLocale::F_HAS_JUST_REGISTERED, $uname));
                $xoopsMailer->send();
            }
        } else {
            echo "<span class='red bold'>{$stop}</span>";
            include $xoops->path('include/registerform.php');
            $reg_form->display();
        }
        $xoops->footer();
        break;

    case 'actv':
    case 'activate':
        $id = $id;
        $actkey = $actkey;
        if (empty($id)) {
            $xoops->redirect('index.php', 1, '');
            exit();
        }
        $member_handler = $xoops->getHandlerMember();
        $thisuser = $member_handler->getUser($id);
        if (!is_object($thisuser)) {
            exit();
        }
        if ($thisuser->getVar('actkey') != $actkey) {
            $xoops->redirect('index.php', 5, XoopsLocale::E_ACTIVATION_KEY_INCORRECT);
        } else {
            if ($thisuser->getVar('level') > 0) {
                $xoops->redirect('user.php', 5, XoopsLocale::E_SELECTED_ACCOUNT_IS_ALREADY_ACTIVATED, false);
            } else {
                if (false != $member_handler->activateUser($thisuser)) {
                    $xoopsConfigUser = $xoops->getConfigs();
                    if ($xoopsConfigUser['activation_type'] == 2) {
                        $myts = MyTextSanitizer::getInstance();
                        $xoopsMailer = $xoops->getMailer();
                        $xoopsMailer->useMail();
                        $xoopsMailer->setTemplate('activated.tpl');
                        $xoopsMailer->assign('SITENAME', $xoops->getConfig('sitename'));
                        $xoopsMailer->assign('ADMINMAIL', $xoops->getConfig('adminmail'));
                        $xoopsMailer->assign('SITEURL', $xoops_url . "/");
                        $xoopsMailer->setToUsers($thisuser);
                        $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                        $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                        $xoopsMailer->setSubject(
                            sprintf(XoopsLocale::F_YOUR_ACCOUNT_AT, $xoops->getConfig('sitename'))
                        );
                        $xoops->header();
                        if (!$xoopsMailer->send()) {
                            printf(XoopsLocale::EF_NOTIFICATION_EMAIL_NOT_SENT_TO, $thisuser->getVar('uname'));
                        } else {
                            printf(XoopsLocale::SF_NOTIFICATION_EMAIL_SENT_TO, $thisuser->getVar('uname'));
                        }
                        $xoops->footer();
                    } else {
                        $xoops->redirect(
                            'user.php',
                            5,
                            XoopsLocale::S_YOUR_ACCOUNT_ACTIVATED . ' ' . XoopsLocale::LOGIN_WITH_REGISTERED_PASSWORD,
                            false
                        );
                    }
                } else {
                    $xoops->redirect('index.php', 5, XoopsLocale::E_ACTIVATION_FAILED);
                }
            }
        }
        break;

    case 'register':
    default:
        $xoops->header();
        $xoops->tpl()->assign('xoops_pagetitle', XoopsLocale::USER_REGISTRATION);
        $xoops->theme()->addMeta(
            'meta',
            'keywords',
            XoopsLocale::USER_REGISTRATION . ", " . XoopsLocale::USERNAME
        ); // FIXME!
        $xoops->theme()->addMeta('meta', 'description', strip_tags($xoopsConfigUser['reg_disclaimer']));
        include $xoops->path('include/registerform.php');
        $reg_form->display();
        $xoops->footer();
        break;
}

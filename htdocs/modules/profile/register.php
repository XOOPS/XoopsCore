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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @author          Jan Pedersen
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'header.php';
$xoops = Xoops::getInstance();

if ($xoops->isUser()) {
    header('location: userinfo.php?uid= ' . $xoops->user->getVar('uid'));
    exit();
}

if (!empty($_GET['op']) && in_array($_GET['op'], array('actv', 'activate'))) {
    header("location: ./activate.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
    exit();
}

$myts = MyTextSanitizer::getInstance();
$xoops->getConfigs();
if (!$xoops->getConfig('allow_register')) {
    $xoops->redirect('index.php', 6, XoopsLocale::E_WE_ARE_CLOSED_FOR_REGISTRATION);
}

$op = !isset($_POST['op']) ? 'register' : $_POST['op'];
$current_step = isset($_POST['step']) ? intval($_POST['step']) : 0;

// The newly introduced variable $_SESSION['profile_post'] is contaminated by $_POST, thus we use an old vaiable to hold uid parameter
$uid = !empty($_SESSION['profile_register_uid']) ? intval($_SESSION['profile_register_uid']) : 0;

// First step is already secured by with the captcha Token so lets check the others
if ($current_step > 0 && !$xoops->security()->check()) {
    $xoops->redirect('user.php', 5, _PROFILE_MA_EXPIRED);
}

$criteria = new CriteriaCompo();
$criteria->setSort("step_order");
$regstep_handler = $xoops->getModuleHandler('regstep');

if (!$steps = $regstep_handler->getAll($criteria, null, false, false)) {
    $xoops->redirect(XOOPS_URL . '/', 6, _PROFILE_MA_NOSTEPSAVAILABLE);
}

foreach (array_keys($steps) as $key) {
    $steps[$key]['step_no'] = $key + 1;
}

$xoops->header('module:profile/profile_register.tpl');

$xoops->tpl()->assign('steps', $steps);
$xoops->tpl()->assign('lang_register_steps', _PROFILE_MA_REGISTER_STEPS);

$xoops->appendConfig('profile_breadcrumbs', array(
    'caption' => _PROFILE_MA_REGISTER,
    'link' => $xoops->url('modules/profile/register.php'),
));

if (isset($steps[$current_step])) {
    $xoops->appendConfig('profile_breadcrumbs', array('caption' => $steps[$current_step]['step_name']));
}

$member_handler = $xoops->getHandlerMember();

/* @var $profile_handler ProfileProfileHandler */
$profile_handler = $xoops->getModuleHandler('profile');

$fields = $profile_handler->loadFields();
$userfields = $profile_handler->getUserVars();

if ($uid == 0) {
    // No user yet? Create one and set default values.
    $newuser = $member_handler->createUser();
    $profile = $profile_handler->create();
    if (count($fields) > 0) {
        /* @var ProfileField $field */
        foreach ($fields as $field) {
            $fieldname = $field->getVar('field_name');
            if (in_array($fieldname, $userfields)) {
                $default = $field->getVar('field_default');
                if ($default === '' || $default === null) {
                    continue;
                }
                $newuser->setVar($fieldname, $default);
            }
        }
    }
} else {
    // We already have a user? Just load it! Security is handled by token so there is no fake uid here.
    $newuser = $member_handler->getUser($uid);
    $profile = $profile_handler->getProfile($uid);
}

// Lets merge current $_POST  with $_SESSION['profile_post'] so we can have access to info submited in previous steps
// Get all fields that we can expect from a $_POST including our private '_message_'
$fieldnames = array();
/* @var ProfileField $field */
foreach ($fields as $field) {
    $fieldnames[] = $field->getVar('field_name');
}
$fieldnames = array_merge($fieldnames, $userfields);
$fieldnames[] = '_message_';

// Get $_POST that matches above criteria, we do not need to store step, tokens, etc
$postfields = array();
foreach ($fieldnames as $fieldname) {
    if (isset($_POST[$fieldname])) {
        $postfields[$fieldname] = $_POST[$fieldname];
    }
}

if ($current_step == 0) {
    // Reset any previous session for first step
    $_SESSION['profile_post'] = array();
    $_SESSION['profile_register_uid'] = null;
} else {
    // Merge current $_POST  with $_SESSION['profile_post']
    $_SESSION['profile_post'] = array_merge($_SESSION['profile_post'], $postfields);
    $_POST = array_merge($_SESSION['profile_post'], $_POST);
}

// Set vars from $_POST/$_SESSION['profile_post']
foreach ($fields as $fieldname => $field) {
    if (!isset($_POST[$fieldname])) {
        continue;
    }

    $value = $field->getValueForSave($_POST[$fieldname]);
    if (in_array($field, $userfields)) {
        $newuser->setVar($fieldname, $value);
    } else {
        $profile->setVar($fieldname, $value);
    }
}

$stop = '';

//Client side validation
if (isset($_POST['step']) && isset($_SESSION['profile_required'])) {
    foreach ($_SESSION['profile_required'] as $name => $title) {
        if (!isset($_POST[$name]) || empty($_POST[$name])) {
            $stop .= sprintf(XoopsLocale::F_ENTER, $title) . '<br />';
        }
    }
}

// Check user data at first step
if ($current_step == 1) {
    $uname = isset($_POST['uname']) ? $myts->stripSlashesGPC(trim($_POST['uname'])) : '';
    $email = isset($_POST['email']) ? $myts->stripSlashesGPC(trim($_POST['email'])) : '';
    $url = isset($_POST['url']) ? $myts->stripSlashesGPC(trim($_POST['url'])) : '';
    $pass = isset($_POST['pass']) ? $myts->stripSlashesGPC(trim($_POST['pass'])) : '';
    $vpass = isset($_POST['vpass']) ? $myts->stripSlashesGPC(trim($_POST['vpass'])) : '';
    $agree_disc = (isset($_POST['agree_disc']) && intval($_POST['agree_disc'])) ? 1 : 0;

    if ($xoops->getConfig('reg_dispdsclmr') != 0 && $xoops->getConfig('reg_disclaimer') != '') {
        if (empty($agree_disc)) {
            $stop .= XoopsLocale::E_YOU_HAVE_TO_AGREE_TO_DISCLAIMER . '<br />';
        }
    }

    $newuser->setVar('uname', $uname);
    $newuser->setVar('email', $email);
    $newuser->setVar('pass', $pass ? password_hash($pass, PASSWORD_DEFAULT) : '');
    $stop .= XoopsUserUtility::validate($newuser, $pass, $vpass);

    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if (!$xoopsCaptcha->verify()) {
        $stop .= $xoopsCaptcha->getMessage();
    }
}

// If the last step required SAVE or if we're on the last step then we will insert/update user on database
if ($current_step > 0 && empty($stop) && (!empty($steps[$current_step - 1]['step_save']) || !isset($steps[$current_step]))) {

    $isNew = $newuser->isNew();

    //Did created an user already? If not then let us set some extra info
    if ($isNew) {
        $uname = isset($_POST['uname']) ? $myts->stripSlashesGPC(trim($_POST['uname'])) : '';
        $email = isset($_POST['email']) ? $myts->stripSlashesGPC(trim($_POST['email'])) : '';
        $url = isset($_POST['url']) ? $myts->stripSlashesGPC(trim($_POST['url'])) : '';
        $pass = isset($_POST['pass']) ? $myts->stripSlashesGPC(trim($_POST['pass'])) : '';
        $newuser->setVar('uname', $uname);
        $newuser->setVar('email', $email);
        $newuser->setVar('pass', $pass ? password_hash($pass, PASSWORD_DEFAULT) : '');
        $actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
        $newuser->setVar('actkey', $actkey, true);
        $newuser->setVar('user_regdate', time(), true);
        $newuser->setVar('uorder', $xoops->getConfig('com_order'), true);
        $newuser->setVar('umode', $xoops->getConfig('com_mode'), true);
        $newuser->setVar('theme', $xoops->getConfig('theme_set'), true);
        $newuser->setVar('user_avatar', 'blank.gif', true);
        if ($xoops->getConfig('activation_type') == 1) {
            $newuser->setVar('level', 1, true);
        } else {
            $newuser->setVar('level', 0, true);
        }
    }

    // Insert/update user and check if we have succeded
    if (!$member_handler->insertUser($newuser)) {
        $stop .= XoopsLocale::E_USER_NOT_REGISTERED . "<br />";
        $stop .= implode('<br />', $newuser->getErrors());
    } else {
        // User inserted! Now insert custom profile fields
        $profile->setVar('profile_id', $newuser->getVar('uid'));
        $profile_handler->insert($profile);

        // We are good! If this is 'was' a new user then we handle notification
        if ($isNew) {
            if ($xoops->getConfig('new_user_notify') == 1 && $xoops->getConfig('new_user_notify_group')) {
                $xoopsMailer = $xoops->getMailer();
                $xoopsMailer->reset();
                $xoopsMailer->useMail();
                $xoopsMailer->setToGroups($member_handler->getGroup($xoops->getConfig('new_user_notify_group')));
                $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                $xoopsMailer->setSubject(sprintf(XoopsLocale::F_NEW_USER_REGISTRATION_AT, $xoops->getConfig('sitename')));
                $xoopsMailer->setBody(sprintf(XoopsLocale::F_HAS_JUST_REGISTERED, $newuser->getVar('uname')));
                $xoopsMailer->send(true);
            }

            $message = "";
            if (!$member_handler->addUserToGroup(XOOPS_GROUP_USERS, $newuser->getVar('uid'))) {
                $message = _PROFILE_MA_REGISTER_NOTGROUP . "<br />";
            } else {
                if ($xoops->getConfig('activation_type') == 1) {
                    XoopsUserUtility::sendWelcome($newuser);
                } else {
                    if ($xoops->getConfig('activation_type') == 0) {
                        $xoopsMailer = $xoops->getMailer();
                        $xoopsMailer->reset();
                        $xoopsMailer->useMail();
                        $xoopsMailer->setTemplate('register.tpl');
                        $xoopsMailer->assign('SITENAME', $xoops->getConfig('sitename'));
                        $xoopsMailer->assign('ADMINMAIL', $xoops->getConfig('adminmail'));
                        $xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
                        $xoopsMailer->assign('X_UPASS', $_POST['vpass']);
                        $xoopsMailer->setToUsers($newuser);
                        $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                        $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                        $xoopsMailer->setSubject(sprintf(XoopsLocale::F_USER_ACTIVATION_KEY_FOR, $newuser->getVar('uname')));
                        if (!$xoopsMailer->send(true)) {
                            $_SESSION['profile_post']['_message_'] = 0;
                        } else {
                            $_SESSION['profile_post']['_message_'] = 1;
                        }
                    } else {
                        if ($xoops->getConfig('activation_type') == 2) {
                            $xoopsMailer = $xoops->getMailer();
                            $xoopsMailer->reset();
                            $xoopsMailer->useMail();
                            $xoopsMailer->setTemplate('adminactivate.tpl');
                            $xoopsMailer->assign('USERNAME', $newuser->getVar('uname'));
                            $xoopsMailer->assign('USEREMAIL', $newuser->getVar('email'));
                            $xoopsMailer->assign('USERACTLINK', XOOPS_URL . "/modules/" . $xoops->module->getVar('dirname', 'n') . '/activate.php?id=' . $newuser->getVar('uid') . '&actkey=' . $newuser->getVar('actkey', 'n'));
                            $xoopsMailer->assign('SITENAME', $xoops->getConfig('sitename'));
                            $xoopsMailer->assign('ADMINMAIL', $xoops->getConfig('adminmail'));
                            $xoopsMailer->assign('SITEURL', XOOPS_URL . "/");
                            $xoopsMailer->setToGroups($member_handler->getGroup($xoops->getConfig('activation_group')));
                            $xoopsMailer->setFromEmail($xoops->getConfig('adminmail'));
                            $xoopsMailer->setFromName($xoops->getConfig('sitename'));
                            $xoopsMailer->setSubject(sprintf(XoopsLocale::F_USER_ACTIVATION_KEY_FOR, $newuser->getVar('uname')));
                            if (!$xoopsMailer->send()) {
                                $_SESSION['profile_post']['_message_'] = 2;
                            } else {
                                $_SESSION['profile_post']['_message_'] = 3;
                            }
                        }
                    }
                }
            }
            if ($message) {
                $xoops->tpl()->append('confirm', $message);
            }
            $_SESSION['profile_register_uid'] = $newuser->getVar('uid');
        }
    }
}

if (!empty($stop) || isset($steps[$current_step])) {
    include_once __DIR__ . '/include/forms.php';
    $current_step = empty($stop) ? $current_step : $current_step - 1;
    $reg_form = profile_getRegisterForm($newuser, $profile, $steps[$current_step]);
    $reg_form->assign($xoops->tpl());
    $xoops->tpl()->assign('current_step', $current_step);
    $xoops->tpl()->assign('stop', $stop);
} else {
    // No errors and no more steps, finish
    $xoops->tpl()->assign('finish', _PROFILE_MA_REGISTER_FINISH);
    $xoops->tpl()->assign('current_step', -1);
    if ($xoops->getConfig('activation_type') == 1 && !empty($_SESSION['profile_post']['pass'])) {
        $xoops->tpl()->assign('finish_login', _PROFILE_MA_FINISH_LOGIN);
        $xoops->tpl()->assign('finish_uname', $newuser->getVar('uname'));
        $xoops->tpl()->assign('finish_pass', htmlspecialchars($_SESSION['profile_post']['pass']));
    }
    if (isset($_SESSION['profile_post']['_message_'])) {
        //todo, if user is activated by admin, then we should inform it along with error messages.  _US_YOURREGMAILNG is not enough
        $messages = array(
            XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' ' . XoopsLocale::EMAIL_HAS_NOT_BEEN_SENT_WITH_ACTIVATION_KEY,
            XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' ' . XoopsLocale::EMAIL_HAS_BEEN_SENT_WITH_ACTIVATION_KEY,
            XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' ' . XoopsLocale::EMAIL_HAS_NOT_BEEN_SENT_WITH_ACTIVATION_KEY,
            XoopsLocale::S_YOU_ARE_NOW_REGISTERED . ' ' . XoopsLocale::PLEASE_WAIT_FOR_ACCOUNT_ACTIVATION
        );
        $xoops->tpl()->assign('finish_message', $messages[$_SESSION['profile_post']['_message_']]);
    }
    $_SESSION['profile_post'] = null;
}

include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php';

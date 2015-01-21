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
 * Mailusers Plugin
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Kazumi Ono (AKA onokazu)
 * @package         system
 * @subpackage      mailusers
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
// Parameters
$limit = 100;
// Get Action type
$op = Request::getString('op', 'list');
$memberslist_id = Request::getArray('memberslist_id', array());

// Call Header
$xoops->header('admin:mailusers/mailusers_send_mail.tpl');
// Define Stylesheet
$xoops->theme()->addBaseStylesheetAssets('modules/system/css/admin.css');
$xoops->theme()->addBaseScriptAssets(array('@jquery','media/xoops/xoops.js','modules/system/js/admin.js'));

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('send_mails.php');

switch ($op) {

    case 'list':
    default:
        $display_criteria = 1;
        $form = new Xoops\Form\ThemeForm(_AM_MAILUSERS_LIST, "mailusers", "send_mails.php", 'post', true);
        //----------------------------------------
        if (!empty($memberslist_id) && (Request::getMethod()=='POST')) {
            $user_count = count($memberslist_id);
            $display_names = "";
            for ($i = 0; $i < $user_count; $i++) {
                $uid_hidden = new Xoops\Form\Hidden("mail_to_user[]", $memberslist_id[$i]);
                $form->addElement($uid_hidden);
                $display_names .= "<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $memberslist_id[$i] . "' rel='external'>" . XoopsUser::getUnameFromId($memberslist_id[$i]) . "</a>, ";
                unset($uid_hidden);
            }
            $users_label = new Xoops\Form\Label(_AM_MAILUSERS_SENDTOUSERS2, substr($display_names, 0, -2));
            $form->addElement($users_label);
            $form->addElement($users_label);
            $display_criteria = 0;
        }
        //----------------------------------------
        if (!empty($display_criteria)) {
            $selected_groups = array();
            $group_select = new Xoops\Form\SelectGroup('<div class="bold spacer">' . _AM_MAILUSERS_GROUPIS . '<span class="bold green">*</span></div>', "mail_to_group", false, $selected_groups, 5, true);

            $lastlog_min = new Xoops\Form\DateSelect(_AM_MAILUSERS_LASTLOGMIN . '<span class="bold green">*</span>', 'mail_lastlog_min');
            $lastlog_min->setValue('');
            $lastlog_max = new Xoops\Form\DateSelect(_AM_MAILUSERS_LASTLOGMAX . '<span class="bold green">*</span>', 'mail_lastlog_max');
            $lastlog_max->setValue('');

            $date = new Xoops\Form\ElementTray('<div class="bold spacer">' . _AM_MAILUSERS_DATE . '</div>', '');
            $date->addElement($lastlog_min);
            $date->addElement($lastlog_max);

            $idle_more = new Xoops\Form\Text(_AM_MAILUSERS_IDLEMORE . '<span class="bold green">*</span>', "mail_idle_more", 2, 5);
            $idle_less = new Xoops\Form\Text(_AM_MAILUSERS_IDLELESS . '<span class="bold green">*</span>', "mail_idle_less", 2, 5);

            $idle = new Xoops\Form\ElementTray('<div class="bold spacer">' . _AM_MAILUSERS_DAY . '</div>', '');
            $idle->addElement($idle_more);
            $idle->addElement($idle_less);

            $regd_min = new Xoops\Form\DateSelect(_AM_MAILUSERS_REGDMIN . '<span class="bold green">*</span>', "mail_regd_min");
            $regd_min->setValue('');
            $regd_max = new Xoops\Form\DateSelect(_AM_MAILUSERS_REGDMAX . '<span class="bold green">*</span>', "mail_regd_max");
            $regd_max->setValue('');

            $regdate = new Xoops\Form\ElementTray('<div class="bold spacer">' . _AM_MAILUSERS_REGDATE . '</div>', '');
            $regdate->addElement($regd_min);
            $regdate->addElement($regd_max);

            $mailok_cbox = new Xoops\Form\Checkbox('', 'mail_mailok');
            $mailok_cbox->addOption(1, _AM_MAILUSERS_MAILOK . '<span class="bold green">*</span>');
            $inactive_cbox = new Xoops\Form\Checkbox('', "mail_inactive");
            $inactive_cbox->addOption(1, _AM_MAILUSERS_INACTIVE . '<span class="bold green">*</span>');
            $inactive_cbox->setExtra("onclick='javascript:disableElement(\"mail_lastlog_min\");disableElement(\"mail_lastlog_max\");disableElement(\"mail_idle_more\");disableElement(\"mail_idle_less\");disableElement(\"mail_to_group[]\");'");

            $criteria_tray = new Xoops\Form\ElementTray(_AM_MAILUSERS_SENDTOUSERS, "<br /><br />");
            $criteria_tray->setDescription('<span class="bold green">*</span>' . _AM_MAILUSERS_OPTIONAL);
            $criteria_tray->addElement($group_select);
            //$criteria_tray->addElement($lastlog);
            $criteria_tray->addElement($date);
            //$criteria_tray->addElement($lastlog_max);
            $criteria_tray->addElement($idle);
            //$criteria_tray->addElement($idle_less);
            $criteria_tray->addElement($regdate);

            $criteria_tray->addElement($mailok_cbox);
            $criteria_tray->addElement($inactive_cbox);

            //$criteria_tray->addElement($regd_max);
            $form->addElement($criteria_tray);
        }
        $fname_text = new Xoops\Form\Text(_AM_MAILUSERS_MAILFNAME, "mail_fromname", 30, 255, $xoops->getConfig('fromname') ? $xoops->getConfig('fromname') :htmlspecialchars($xoops->getConfig('sitename'), ENT_QUOTES));
        $fromemail = $xoops->getConfig('from') ? $xoops->getConfig('from') : $xoops->user->getVar("email", "E");
        $femail_text = new Xoops\Form\Text(_AM_MAILUSERS_MAILFMAIL, "mail_fromemail", 30, 255, $fromemail);
        $subject_caption = _AM_MAILUSERS_MAILSUBJECT . "<br /><br /><span style='font-size:x-small;font-weight:bold;'>" . _AM_MAILUSERS_MAILTAGS . "</span><br /><span style='font-size:x-small;font-weight:normal;'>" . _AM_MAILUSERS_MAILTAGS2 . "</span>";
        $subject_text = new Xoops\Form\Text($subject_caption, "mail_subject", 50, 255);
        $body_caption = _AM_MAILUSERS_MAILBODY . "<br /><br /><span style='font-size:x-small;font-weight:bold;'>" . _AM_MAILUSERS_MAILTAGS . "</span><br /><span style='font-size:x-small;font-weight:normal;'>" . _AM_MAILUSERS_MAILTAGS1 . "<br />" . _AM_MAILUSERS_MAILTAGS2 . "<br />" . _AM_MAILUSERS_MAILTAGS3 . "<br />" . _AM_MAILUSERS_MAILTAGS4 . "</span>";
        $editor_configs = array();
        $editor_configs["name"] = "mail_body";
        $editor_configs["value"] = '';
        $editor_configs["rows"] = 20;
        $editor_configs["cols"] = 100;
        $editor_configs["width"] = "100%";
        $editor_configs["height"] = "400px";
        $editor_configs["editor"] = $xoops->getModuleConfig('mailusers_editor');
        $body_text = new Xoops\Form\Editor($body_caption, "mail_body", $editor_configs);
        //$body_text = new Xoops\Form\TextArea($body_caption, "mail_body", "", 10);
        $to_checkbox = new Xoops\Form\Checkbox(_AM_MAILUSERS_SENDTO, "mail_send_to", "mail");
        $to_checkbox->addOption("mail", _AM_MAILUSERS_EMAIL);
        $to_checkbox->addOption("pm", _AM_MAILUSERS_PM);
        $start_hidden = new Xoops\Form\Hidden("mail_start", 0);
        $op_hidden = new Xoops\Form\Hidden("op", "send");
        $submit_button = new Xoops\Form\Button("", "mail_submit", XoopsLocale::A_SEND, "submit");

        $form->addElement($fname_text);
        $form->addElement($femail_text);
        $form->addElement($subject_text);
        $form->addElement($body_text);
        $form->addElement($to_checkbox);
        $form->addElement($op_hidden);
        $form->addElement($start_hidden);
        $form->addElement($submit_button);
        $form->setRequired($subject_text);
        $form->setRequired($body_text);
        $xoops->tpl()->assign('form', $form->render());
        break;

    // Send
    case 'send':

        $mail_send_to = Request::getArray('mail_send_to', array('mail'));
        $mail_inactive = Request::getInt('mail_inactive', 0);
        $mail_mailok = Request::getInt('mail_mailok', 0);
        $mail_lastlog_min = Request::getString('mail_lastlog_min', '');
        $mail_lastlog_max = Request::getString('mail_lastlog_max', '');
        $mail_idle_more = Request::getInt('mail_idle_more', 0);
        $mail_idle_less = Request::getInt('mail_idle_less', 0);
        $mail_regd_min = Request::getString('mail_regd_min', '');
        $mail_regd_max = Request::getString('mail_regd_max', '');
        $mail_to_group = Request::getArray('mail_to_group', array());
        $mail_to_group = array_map("intval", $mail_to_group);
        $mail_start = Request::getInt('mail_start', 0);
        $mail_to_user = Request::getArray('mail_to_user', array());
        $mail_to_user = array_map("intval", $mail_to_user);

        $mail_fromname = Request::getString('mail_fromname');
        $mail_fromemail = Request::getString('mail_fromemail');
        $mail_subject = Request::getString('mail_subject');
        $mail_body = Request::getString('mail_body');

        $count_criteria = 0; // user count via criteria;
        if (!empty($mail_send_to)) {
            $added = array();
            $added_id = array();
            $criteria = array();
            if ($mail_inactive) {
                $criteria[] = "level = 0";
            } else {
                if ($mail_mailok) {
                    $criteria[] = 'user_mailok = 1';
                }
                if ($mail_lastlog_min) {
                    $time = strtotime(trim($mail_lastlog_min));
                    if ($time > 0) {
                        $criteria[] = "last_login > $time";
                    }
                }
                if ($mail_lastlog_max) {
                    $time = strtotime(trim($mail_lastlog_max));
                    if ($time > 0) {
                        $criteria[] = "last_login < $time";
                    }
                }
                if ($mail_idle_more) {
                    $time = 60 * 60 * 24 * $mail_idle_more;
                    $time = time() - $time;
                    if ($time > 0) {
                        $criteria[] = "last_login < $time";
                    }
                }
                if ($mail_idle_less) {
                    $time = 60 * 60 * 24 * $mail_idle_less;
                    $time = time() - $time;
                    if ($time > 0) {
                        $criteria[] = "last_login > $time";
                    }
                }
            }
            if ($mail_regd_min) {
                $time = strtotime(trim($mail_regd_min));
                if ($time > 0) {
                    $criteria[] = "user_regdate > $time";
                }
            }
            if ($mail_regd_max) {
                $time = strtotime(trim($mail_regd_max));
                if ($time > 0) {
                    $criteria[] = "user_regdate < $time";
                }
            }

            if (!empty($criteria) || !empty($mail_to_group)) {
                $criteria_object = new CriteriaCompo();
                $criteria_object->setStart($mail_start);
                $criteria_object->setLimit($limit);
                foreach ($criteria as $c) {
                    list ($field, $op, $value) = explode(' ', $c);
                    $crit = new Criteria($field, $value, $op);
                    $crit->prefix = "u";
                    $criteria_object->add($crit, 'AND');
                }
                $member_handler = $xoops->getHandlerMember();
                $getusers = $member_handler->getUsersByGroupLink($mail_to_group, $criteria_object, true);
                $count_criteria = $member_handler->getUserCountByGroupLink($mail_to_group, $criteria_object);
                foreach ($getusers as $getuser) {
                    /* @var $getuser XoopsUser */
                    if (!in_array($getuser->getVar("uid"), $added_id)) {
                        $added[] = $getuser;
                        $added_id[] = $getuser->getVar("uid");
                    }
                }
            }

            foreach ($mail_to_user as $to_user) {
                if (!in_array($to_user, $added_id)) {
                    $added[] = new XoopsUser($to_user);
                    $added_id[] = $to_user;
                }
            }
            $added_count = count($added);

            //OpenTable();
            if ($added_count > 0) {
                $myts = MyTextSanitizer::getInstance();
                $xoopsMailer = $xoops->getMailer();
                for ($i = 0; $i < $added_count; $i++) {
                    $xoopsMailer->setToUsers($added[$i]);
                }
                $xoopsMailer->setFromName($mail_fromname);
                $xoopsMailer->setFromEmail($mail_fromemail);
                $xoopsMailer->setSubject($mail_subject);
                $xoopsMailer->setBody($mail_body);
                if (in_array("mail", $mail_send_to)) {
                    $xoopsMailer->useMail();
                }
                if (in_array("pm", $mail_send_to) && !$mail_inactive) {
                    $xoopsMailer->usePM();
                }
                $xoopsMailer->send(true);
                $xoops->tpl()->assign('sucess', $xoopsMailer->getSuccess());
                $xoops->tpl()->assign('errors', $xoopsMailer->getErrors());

                if ($count_criteria > $limit) {
                    //todo, is this url corret?
                    $form = new Xoops\Form\ThemeForm(_AM_MAILUSERS_SENDTOUSERS2, "mailusers", "send_mails.php", 'post', true);
                    foreach ($mail_to_group as $mailgroup) {
                        $group_hidden = new Xoops\Form\Hidden("mail_to_group[]", $mailgroup);
                        $form->addElement($group_hidden);
                    }
                    $inactive_hidden = new Xoops\Form\Hidden("mail_inactive", $mail_inactive);
                    $lastlog_min_hidden = new Xoops\Form\Hidden("mail_lastlog_min", $myts->htmlSpecialChars($mail_lastlog_min));
                    $lastlog_max_hidden = new Xoops\Form\Hidden("mail_lastlog_max", $myts->htmlSpecialChars($mail_lastlog_max));
                    $regd_min_hidden = new Xoops\Form\Hidden("mail_regd_min", $myts->htmlSpecialChars($mail_regd_min));
                    $regd_max_hidden = new Xoops\Form\Hidden("mail_regd_max", $myts->htmlSpecialChars($mail_regd_max));
                    $idle_more_hidden = new Xoops\Form\Hidden("mail_idle_more", $myts->htmlSpecialChars($mail_idle_more));
                    $idle_less_hidden = new Xoops\Form\Hidden("mail_idle_less", $myts->htmlSpecialChars($mail_idle_less));
                    $fname_hidden = new Xoops\Form\Hidden("mail_fromname", $myts->htmlSpecialChars($mail_fromname));
                    $femail_hidden = new Xoops\Form\Hidden("mail_fromemail", $myts->htmlSpecialChars($mail_fromemail));
                    $subject_hidden = new Xoops\Form\Hidden("mail_subject", $myts->htmlSpecialChars($mail_subject));
                    $body_hidden = new Xoops\Form\Hidden("mail_body", $myts->htmlSpecialChars($mail_body));
                    $start_hidden = new Xoops\Form\Hidden("mail_start", $mail_start + $limit);
                    $mail_mailok_hidden = new Xoops\Form\Hidden("mail_mailok", $myts->htmlSpecialChars($mail_mailok));
                    $op_hidden = new Xoops\Form\Hidden("op", "send");
                    $submit_button = new Xoops\Form\Button("", "mail_submit", _AM_MAILUSERS_SENDNEXT, "submit");
                    $sent_label = new Xoops\Form\Label(_AM_MAILUSERS_SENT, sprintf(_AM_MAILUSERS_SENTNUM, $mail_start + 1, $mail_start + $limit, $count_criteria + $added_count - $limit));
                    $form->addElement($sent_label);
                    $form->addElement($inactive_hidden);
                    $form->addElement($lastlog_min_hidden);
                    $form->addElement($lastlog_max_hidden);
                    $form->addElement($regd_min_hidden);
                    $form->addElement($regd_max_hidden);
                    $form->addElement($idle_more_hidden);
                    $form->addElement($idle_less_hidden);
                    $form->addElement($fname_hidden);
                    $form->addElement($femail_hidden);
                    $form->addElement($subject_hidden);
                    $form->addElement($body_hidden);
                    $form->addElement($op_hidden);
                    $form->addElement($start_hidden);
                    $form->addElement($mail_mailok_hidden);
                    foreach ($mail_send_to as $v) {
                        $form->addElement(new Xoops\Form\Hidden("mail_send_to[]", $v));
                    }
                    $form->addElement($submit_button);
                    $xoops->tpl()->assign('form', $form->render());
                } else {
                    $xoops->tpl()->assign('sucess', _AM_MAILUSERS_SENDCOMP);
                }
            } else {
                $xoops->tpl()->assign('errors', _AM_MAILUSERS_NOUSERMATCH);
            }
        }
        break;
}
// Call Footer
$xoops->footer();

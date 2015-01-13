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
 * Private message module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         pm
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

if (!defined('XOOPS_MAINFILE_INCLUDED')) {
    include_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';
} else {
    chdir(XOOPS_ROOT_PATH . '/modules/pm/');
}
$xoops = Xoops::getInstance();
$xoops->loadLanguage('main', 'pm');

$reply = !empty($_GET['reply']) ? 1 : 0;
$send = !empty($_GET['send']) ? 1 : 0;
$send2 = !empty($_GET['send2']) ? 1 : 0;
$sendmod = !empty($_POST['sendmod']) ? 1 : 0; // send from other modules with post data
$to_userid = isset($_GET['to_userid']) ? intval($_GET['to_userid']) : 0;
$msg_id = isset($_GET['msg_id']) ? intval($_GET['msg_id']) : 0;

if (empty($_GET['refresh']) && isset($_POST['op']) && $_POST['op'] != "submit") {
    $jump = "pmlite.php?refresh=" . time();
    if ($send == 1) {
        $jump .= "&amp;send={$send}";
    } else {
        if ($send2 == 1) {
            $jump .= "&amp;send2={$send2}&amp;to_userid={$to_userid}";
        } else {
            if ($reply == 1) {
                $jump .= "&amp;reply={$reply}&amp;msg_id={$msg_id}";
            } else {
            }
        }
    }
    header('location: ' . $jump);
    exit();
}

if (!$xoops->isUser()) {
    $xoops->redirect(XOOPS_URL, 3, XoopsLocale::E_NO_ACCESS_PERMISSION);
}
$xoops->simpleHeader();
$xoops->disableErrorReporting();

$myts = MyTextSanitizer::getInstance();
if (isset($_POST['op']) && $_POST['op'] == "submit") {
    $member_handler = $xoops->getHandlerMember();
    $count = $member_handler->getUserCount(new Criteria('uid', intval($_POST['to_userid'])));
    $tpl = new XoopsTpl();
    if ($count != 1) {
        $error_message = _PM_USERNOEXIST;
        $error_message .= "<br />" . _PM_PLZTRYAGAIN;
        $error_message .= "<br />[ <a href='javascript:history.go(-1)'>" . _PM_GOBACK . "</a> ]";
        $tpl->assign('error_message', $error_message);
    } else {
        if ($xoops->security()->check()) {
            $pm_handler = $xoops->getModuleHandler('message', 'pm');
            $pm = $pm_handler->create();
            $pm->setVar("msg_time", time());
            if (isset($_POST['icon'])) {
                $pm->setVar("msg_image", $_POST['icon']);
            }
            $pm->setVar("subject", $_POST['subject']);
            $pm->setVar("msg_text", $_POST['message']);
            $pm->setVar("to_userid", $_POST['to_userid']);
            $pm->setVar("from_userid", $xoops->user->getVar("uid"));
            if (isset($_REQUEST['savecopy']) && $_REQUEST['savecopy'] == 1) {
                //PMs are by default not saved in outbox
                $pm->setVar('from_delete', 0);
            }
            if (!$pm_handler->insert($pm)) {
                $error_message = $pm->getHtmlErrors();
                $error_message .= "<br /><a href='javascript:history.go(-1)'>" . _PM_GOBACK . "</a>";
                $tpl->assign('error_message', $error_message);
            } else {
                // @todo: Send notification email if user has selected this in the profile
                $info_message = _PM_MESSAGEPOSTED;
                $info_message .= "<br />";
                $info_message .= "<br /><a href=\"javascript:window.opener.location='" . XOOPS_URL . "/viewpmsg.php';window.close();\">" . _PM_CLICKHERE . "</a>";
                $info_message .= "<br /><br /><a href=\"javascript:window.close();\">" . _PM_ORCLOSEWINDOW . "</a>";
                $tpl->assign('info_message', $info_message);
            }
        } else {
            $error_message = implode('<br />', $xoops->security()->getErrors());
            $error_message .= "<br /><a href=\"javascript:window.close();\">" . _PM_ORCLOSEWINDOW . "</a>";
            $tpl->assign('error_message', $error_message);
        }
    }
    $tpl->display("module:pm/pm_pmlite.tpl");

} else {
    $message = '';
    $pm_uname = '';
    if ($reply == 1 || $send == 1 || $send2 == 1 || $sendmod == 1) {
        if ($reply == 1) {
            $pm_handler = $xoops->getModuleHandler('message', 'pm');
            $pm = $pm_handler->get($msg_id);
            if ($pm->getVar("to_userid") == $xoops->user->getVar('uid')) {
                $pm_uname = XoopsUser::getUnameFromId($pm->getVar("from_userid"));
                $message = "[quote]\n";
                $message .= sprintf(_PM_USERWROTE, $pm_uname);
                $message .= "\n" . $pm->getVar("msg_text", "E") . "\n[/quote]";
            } else {
                unset($pm);
                $reply = $send2 = 0;
            }
        }

        $tpl = new XoopsTpl();
        $form = new Xoops\Form\ThemeForm('', 'pmform', 'pmlite.php', 'post', true);

        if ($reply == 1) {
            $subject = $pm->getVar('subject', 'E');
            if (!preg_match("/^" . XoopsLocale::C_RE . "/i", $subject)) {
                $subject = XoopsLocale::C_RE . ' ' . $subject;
            }
            $form->addElement(new Xoops\Form\Label(_PM_TO, $pm_uname));
            $form->addElement(new Xoops\Form\Hidden('to_userid', $pm->getVar("from_userid")));
        } else {
            if ($sendmod == 1) {
                $form->addElement(new Xoops\Form\Label(_PM_TO, XoopsUser::getUnameFromId($_POST["to_userid"])));
                $form->addElement(new Xoops\Form\Hidden('to_userid', $_POST["to_userid"]));
                $subject = $myts->htmlSpecialChars($myts->stripSlashesGPC($_POST['subject']));
                $message = $myts->htmlSpecialChars($myts->stripSlashesGPC($_POST['message']));
            } else {
                if ($send2 == 1) {
                    $form->addElement(new Xoops\Form\Label(_PM_TO, XoopsUser::getUnameFromId($to_userid, false)));
                    $form->addElement(new Xoops\Form\Hidden('to_userid', $to_userid));
                } else {
                    $form->addElement(new Xoops\Form\SelectUser(_PM_TO, 'to_userid'));
                }
                $subject = "";
                $message = "";
            }
        }
        $form->addElement(new Xoops\Form\Text(_PM_SUBJECTC, 'subject', 4, 100, $subject), true);

        $icons = new Xoops\Form\Radio(XoopsLocale::MESSAGE_ICON, 'msg_image', '', true);
        $subject_icons = XoopsLists::getSubjectsList();
        foreach (array_keys($subject_icons) as $i) {
            $icons->addOption($i, "<img src='" . $xoops->url("images/subject/") . $i . "' alt='" . $i . "' />");
        }
        $form->addElement($icons, false);
        $form->addElement(new Xoops\Form\DhtmlTextArea(_PM_MESSAGEC, 'message', $message, 8, 37), true);
        $form->addElement(new Xoops\Form\RadioYesNo(_PM_SAVEINOUTBOX, 'savecopy', 0));
        $form->addElement(new Xoops\Form\Hidden('op', 'submit'));

        $buttons = new Xoops\Form\ElementTray('');
        $buttons ->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
        $buttons ->addElement(new Xoops\Form\Button('', 'reset', _PM_CLEAR, 'reset'));
        $cancel_send = new Xoops\Form\Button('', 'cancel', _PM_CANCELSEND, 'button');
        $cancel_send->setExtra("onclick='javascript:window.close();'");
        $buttons ->addElement($cancel_send);
        $form->addElement($buttons);
        $tpl->assign('form', $form->render());
        $tpl->display("module:pm/pm_pmlite.tpl");
    }
}
$xoops->simpleFooter();

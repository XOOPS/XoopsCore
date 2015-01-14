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

include_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';
$xoops = Xoops::getInstance();

if (!$xoops->isUser()) {
    $xoops->redirect(XOOPS_URL, 3, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$xoops->disableModuleCache(); //disable caching since the URL will be the same, but content different from one user to another
$xoops->header('module:pm/pm_viewpmsg.tpl');

$valid_op_requests = array('out', 'save', 'in');
$_REQUEST['op'] = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : 'in';

$start = empty($_REQUEST["start"]) ? 0 : intval($_REQUEST["start"]);
/* @var $pm_handler PmMessageHandler */
$pm_handler = $xoops->getModuleHandler('message');

if (isset($_POST['delete_messages']) && (isset($_POST['msg_id']) || isset($_POST['msg_ids']))) {
    if (!$xoops->security()->check()) {
        $xoops->tpl()->assign('errormsg', implode('<br />', $xoops->security()->getErrors()));
    } else {
        if (empty($_REQUEST['ok'])) {
            $xoops->confirm(array(
                                 'ok' => 1, 'delete_messages' => 1, 'op' => $_REQUEST['op'],
                                 'msg_ids' => json_encode(array_map("intval", $_POST['msg_id']))
                            ), $_SERVER['REQUEST_URI'], XoopsLocale::Q_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MESSAGES);
            $xoops->footer();
        } else {
            $clean_msg_id = json_decode($_POST['msg_ids'], true, 2);
            if (!empty($clean_msg_id)) {
                $clean_msg_id = array_map("intval", $clean_msg_id);
            }
            $size = count($clean_msg_id);
            $msg =& $clean_msg_id;
            for ($i = 0; $i < $size; $i++) {
                $pm = $pm_handler->get($msg[$i]);
                if ($pm->getVar('to_userid') == $xoops->user->getVar('uid')) {
                    $pm_handler->setTodelete($pm);
                } else {
                    if ($pm->getVar('from_userid') == $xoops->user->getVar('uid')) {
                        $pm_handler->setFromdelete($pm);
                    }
                }
                unset($pm);
            }
            $xoops->tpl()->assign('msg', XoopsLocale::S_YOUR_MESSAGES_DELETED);
        }
    }
}
if (isset($_POST['move_messages']) && isset($_POST['msg_id'])) {
    if (!$xoops->security()->check()) {
        $xoops->tpl()->assign('errormsg', implode('<br />', $xoops->security()->getErrors()));
    } else {
        $size = count($_POST['msg_id']);
        $msg = $_POST['msg_id'];
        if ($_POST['op'] == 'save') {
            for ($i = 0; $i < $size; $i++) {
                $pm = $pm_handler->get($msg[$i]);
                if ($pm->getVar('to_userid') == $xoops->user->getVar('uid')) {
                    $pm_handler->setTosave($pm, 0);
                } else {
                    if ($pm->getVar('from_userid') == $xoops->user->getVar('uid')) {
                        $pm_handler->setFromsave($pm, 0);
                    }
                }
                unset($pm);
            }
        } else {
            if (!$xoops->user->isAdmin()) {
                $total_save = $pm_handler->getSavecount();
                $size = min($size, $xoops->getModuleConfig('max_save') - $total_save);
            }
            for ($i = 0; $i < $size; $i++) {
                $pm = $pm_handler->get($msg[$i]);
                if ($_POST['op'] == 'in') {
                    $pm_handler->setTosave($pm);
                } else {
                    if ($_POST['op'] == 'out') {
                        $pm_handler->setFromsave($pm);
                    }
                }
                unset($pm);
            }
        }
        if ($_POST['op'] == 'save') {
            $xoops->tpl()->assign('msg', _PM_UNSAVED);
        } else {
            if (isset($total_save) && !$xoops->user->isAdmin()) {
                $xoops->tpl()->assign('msg', sprintf(_PM_SAVED_PART, $xoops->getModuleConfig('max_save'), $i));
            } else {
                $xoops->tpl()->assign('msg', _PM_SAVED_ALL);
            }
        }
    }
}
if (isset($_REQUEST['empty_messages'])) {
    if (!$xoops->security()->check()) {
        $xoops->tpl()->assign('errormsg', implode('<br />', $xoops->security()->getErrors()));
    } else {
        if (empty($_REQUEST['ok'])) {
            $xoops->confirm(array(
                                 'ok' => 1, 'empty_messages' => 1, 'op' => $_REQUEST['op']
                            ), $_SERVER['REQUEST_URI'], _PM_RUSUREEMPTY);
            $xoops->footer();
        } else {
            if ($_POST['op'] == 'save') {
                $crit_to = new CriteriaCompo(new Criteria('to_delete', 0));
                $crit_to->add(new Criteria('to_save', 1));
                $crit_to->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
                $crit_from = new CriteriaCompo(new Criteria('from_delete', 0));
                $crit_from->add(new Criteria('from_save', 1));
                $crit_from->add(new Criteria('from_userid', $xoops->user->getVar('uid')));
                $criteria = new CriteriaCompo($crit_to);
                $criteria->add($crit_from, "OR");
            } else {
                if ($_POST['op'] == 'out') {
                    $criteria = new CriteriaCompo(new Criteria('from_delete', 0));
                    $criteria->add(new Criteria('from_userid', $xoops->user->getVar('uid')));
                    $criteria->add(new Criteria('from_save', 0));
                } else {
                    $criteria = new CriteriaCompo(new Criteria('to_delete', 0));
                    $criteria->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
                    $criteria->add(new Criteria('to_save', 0));
                }
            }
            /*
            * The following method has critical scalability problem !
            * deleteAll method should be used instead
            */
            $pms = $pm_handler->getObjects($criteria);
            unset($criteria);
            if (count($pms) > 0) {
                foreach (array_keys($pms) as $i) {
                    if ($pms[$i]->getVar('to_userid') == $xoops->user->getVar('uid')) {
                        if ($_POST['op'] == 'save') {
                            $pm_handler->setTosave($pms[$i], 0);
                        } else {
                            if ($_POST['op'] == 'in') {
                                $pm_handler->setTodelete($pms[$i]);
                            }
                        }
                    }
                    if ($pms[$i]->getVar('from_userid') == $xoops->user->getVar('uid')) {
                        if ($_POST['op'] == 'save') {
                            $pm_handler->setFromsave($pms[$i], 0);
                        } else {
                            if ($_POST['op'] == 'out') {
                                $pm_handler->setFromdelete($pms[$i]);
                            }
                        }
                    }
                }
            }
            $xoops->tpl()->assign('msg', _PM_EMPTIED);
        }
    }
}

if ($_REQUEST['op'] == "out") {
    $criteria = new CriteriaCompo(new Criteria('from_delete', 0));
    $criteria->add(new Criteria('from_userid', $xoops->user->getVar('uid')));
    $criteria->add(new Criteria('from_save', 0));
} else {
    if ($_REQUEST['op'] == "save") {
        $crit_to = new CriteriaCompo(new Criteria('to_delete', 0));
        $crit_to->add(new Criteria('to_save', 1));
        $crit_to->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
        $crit_from = new CriteriaCompo(new Criteria('from_delete', 0));
        $crit_from->add(new Criteria('from_save', 1));
        $crit_from->add(new Criteria('from_userid', $xoops->user->getVar('uid')));
        $criteria = new CriteriaCompo($crit_to);
        $criteria->add($crit_from, "OR");
    } else {
        $criteria = new CriteriaCompo(new Criteria('to_delete', 0));
        $criteria->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
        $criteria->add(new Criteria('to_save', 0));
    }
}
$total_messages = $pm_handler->getCount($criteria);
$criteria->setStart($start);
$criteria->setLimit($xoops->getModuleConfig('perpage'));
$criteria->setSort("msg_time");
$criteria->setOrder("DESC");
$pm_arr = $pm_handler->getAll($criteria, null, false, false);
unset($criteria);

$xoops->tpl()->assign('total_messages', $total_messages);
$xoops->tpl()->assign('op', $_REQUEST['op']);

if ($total_messages > $xoops->getModuleConfig('perpage')) {
    $nav = new XoopsPageNav($total_messages, $xoops->getModuleConfig('perpage'), $start, "start", 'op=' . htmlspecialchars($_REQUEST['op']));
    $xoops->tpl()->assign('pagenav', $nav->renderNav(4));
}

$xoops->tpl()->assign('display', $total_messages > 0);
$xoops->tpl()->assign('anonymous', $xoops->getConfig('anonymous'));
if (count($pm_arr) > 0) {
    foreach (array_keys($pm_arr) as $i) {
        if (isset($_REQUEST['op']) && $_REQUEST['op'] == "out") {
            $uids[] = $pm_arr[$i]['to_userid'];
        } else {
            $uids[] = $pm_arr[$i]['from_userid'];
        }
    }
    $member_handler = $xoops->getHandlerMember();
    $senders = $member_handler->getUserList(new Criteria('uid', "(" . implode(", ", array_unique($uids)) . ")", "IN"));
    foreach (array_keys($pm_arr) as $i) {
        $message = $pm_arr[$i];
        $message['msg_time'] = XoopsLocale::formatTimestamp($message["msg_time"]);
        if (isset($_REQUEST['op']) && $_REQUEST['op'] == "out") {
            $message['postername'] = $senders[$pm_arr[$i]['to_userid']];
            $message['posteruid'] = $pm_arr[$i]['to_userid'];
        } else {
            $message['postername'] = $senders[$pm_arr[$i]['from_userid']];
            $message['posteruid'] = $pm_arr[$i]['from_userid'];
        }
        $message['msg_no'] = $i;
        $xoops->tpl()->append('messages', $message);
    }
}

$send_button = new Xoops\Form\Button('', 'send', XoopsLocale::A_SEND);
$send_button->setExtra("onclick='javascript:openWithSelfMain(\"" . XOOPS_URL . "/modules/pm/pmlite.php?send=1\", \"pmlite\", 750,720);'");
$delete_button = new Xoops\Form\Button('', 'delete_messages', XoopsLocale::A_DELETE, 'submit');
$move_button = new Xoops\Form\Button('', 'move_messages', ($_REQUEST['op'] == 'save') ? _PM_UNSAVE
            : _PM_TOSAVE, 'submit');
$empty_button = new Xoops\Form\Button('', 'empty_messages', _PM_EMPTY, 'submit');

$pmform = new Xoops\Form\ThemeForm('', 'pmform', 'viewpmsg.php', 'post', true);
$pmform->addElement($send_button);
$pmform->addElement($move_button);
$pmform->addElement($delete_button);
$pmform->addElement($empty_button);
$pmform->addElement(new Xoops\Form\Hidden('op', $_REQUEST['op']));
$pmform->assign($xoops->tpl());
$xoops->footer();

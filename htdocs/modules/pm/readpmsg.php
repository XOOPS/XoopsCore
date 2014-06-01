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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         pm
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';
$xoops = Xoops::getInstance();

if (!$xoops->isUser()) {
    $xoops->redirect(XOOPS_URL, 3, XoopsLocale::E_NO_ACCESS_PERMISSION);
}
$valid_op_requests = array('out', 'save', 'in');
$_REQUEST['op'] = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : 'in' ;
$msg_id = empty($_REQUEST['msg_id']) ? 0 : intval($_REQUEST['msg_id']);
/* @var $pm_handler PmMessageHandler */
$pm_handler = $xoops->getModuleHandler('message');
if ($msg_id > 0) {
    $pm = $pm_handler->get($msg_id);
} else {
    $pm = null;
}

if (is_object($pm) && !$xoops->user->isAdmin() && ($pm->getVar('from_userid') != $xoops->user->getVar('uid'))
    && ($pm->getVar('to_userid') != $xoops->user->getVar('uid'))
){
    $xoops->redirect(XOOPS_URL . '/modules/' . $xoops->module->getVar("dirname", "n") . '/index.php', 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

if (is_object($pm) && !empty($_POST['action']) ) {
    if (!$xoops->security()->check()) {
        echo implode('<br />', $xoops->security()->getErrors());
        exit();
    }
    $res = false;
    if (!empty($_REQUEST['email_message'])) {
        $res = $pm_handler->sendEmail($pm, $xoops->user);
    } else if (!empty($_REQUEST['move_message'])
               && $_REQUEST['op'] != 'save'
               && !$xoops->user->isAdmin()
               && $pm_handler->getSavecount() >= $xoops->getModuleConfig('max_save')
    ) {
        $res_message = sprintf(_PM_SAVED_PART, $xoops->getModuleConfig('max_save'), 0);
    } else {
        switch ($_REQUEST['op']) {
            case 'out':
                if ($pm->getVar('from_userid') != $xoops->user->getVar('uid')) break;
                if (!empty($_REQUEST['delete_message'])) {
                    $res = $pm_handler->setFromdelete($pm);
                } else if (!empty($_REQUEST['move_message'])) {
                    $res = $pm_handler->setFromsave($pm);
                }
                break;
            case 'save':
                if ($pm->getVar('to_userid') == $xoops->user->getVar('uid')) {
                    if (!empty($_REQUEST['delete_message'])) {
                        $res1 = $pm_handler->setTodelete($pm);
                        $res1 = ($res1) ? $pm_handler->setTosave($pm, 0) : false;
                    } else if (!empty($_REQUEST['move_message'])) {
                        $res1 = $pm_handler->setTosave($pm, 0);
                    }
                }
                if ($pm->getVar('from_userid') == $xoops->user->getVar('uid')) {
                    if (!empty($_REQUEST['delete_message'])) {
                        $res2 = $pm_handler->setFromDelete($pm);
                        $res2 = ($res2) ? $pm_handler->setFromsave($pm, 0) : false;
                    } else if (!empty($_REQUEST['move_message'])) {
                        $res2 = $pm_handler->setFromsave($pm, 0);
                    }
                }
                $res = $res1 && $res2;
                break;

            case 'in':
            default:
                if ($pm->getVar('to_userid') != $xoops->user->getVar('uid')) {
                    break;
                }
                if (!empty($_REQUEST['delete_message'])) {
                    $res = $pm_handler->setTodelete($pm);
                } else if (!empty($_REQUEST['move_message'])) {
                    $res = $pm_handler->setTosave($pm);
                }
                break;
        }
    }
    $res_message = isset($res_message) ? $res_message : (($res) ? _PM_ACTION_DONE : _PM_ACTION_ERROR);
    $xoops->redirect('viewpmsg.php?op=' . htmlspecialchars($_REQUEST['op'] ) , 2, $res_message);
}
$start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
$total_messages = !empty($_GET['total_messages']) ? intval($_GET['total_messages']) : 0;
$xoops->header('pm_readpmsg.tpl');

if (!is_object($pm)) {
    if ($_REQUEST['op'] == "out") {
        $criteria = new CriteriaCompo(new Criteria('from_delete', 0));
        $criteria->add(new Criteria('from_userid', $xoops->user->getVar('uid')));
        $criteria->add(new Criteria('from_save', 0));
    } else if ($_REQUEST['op'] == "save") {
        $crit_to = new CriteriaCompo(new Criteria('to_delete', 0));
        $crit_to->add(new Criteria('to_save', 1));
        $crit_to->add(new Criteria('to_userid',$xoops->user->getVar('uid')));
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

    $criteria->setLimit(1);
    $criteria->setStart($start);
    $criteria->setSort('msg_time');
    $criteria->setOrder("DESC");
    list($pm) = $pm_handler->getObjects($criteria);
}

$pmform = new XoopsThemeForm('', 'pmform', 'readpmsg.php', 'post', true);
if (is_object($pm) && !empty($pm)) {
    if ($pm->getVar('from_userid') != $xoops->user->getVar('uid')) {
        $reply_button = new XoopsFormButton('', 'send', XoopsLocale::A_REPLY);
        $reply_button->setExtra("onclick='javascript:openWithSelfMain(\"" . XOOPS_URL . "/modules/pm/pmlite.php?reply=1&amp;msg_id={$msg_id}\", \"pmlite\", 565,500);'");
        $pmform->addElement($reply_button);
    }
    $pmform->addElement(new XoopsFormButton('', 'delete_message', XoopsLocale::A_DELETE, 'submit'));
    $pmform->addElement(new XoopsFormButton('', 'move_message', ($_REQUEST['op'] == 'save') ? _PM_UNSAVE : _PM_TOSAVE, 'submit'));
    $pmform->addElement(new XoopsFormButton('', 'email_message', _PM_EMAIL, 'submit'));
    $pmform->addElement(new XoopsFormHidden('msg_id', $pm->getVar("msg_id")));
    $pmform->addElement(new XoopsFormHidden('op', $_REQUEST['op']));
    $pmform->addElement(new XoopsFormHidden('action', 1));
    $pmform->assign($xoops->tpl());

    if ($pm->getVar("from_userid") == $xoops->user->getVar("uid")) {
        $poster = new XoopsUser($pm->getVar("to_userid"));
    } else {
        $poster = new XoopsUser($pm->getVar("from_userid"));
    }
    if (!is_object($poster)) {
        $xoops->tpl()->assign('poster', false);
        $xoops->tpl()->assign('anonymous', $xoopsConfig['anonymous']);
    } else {
        $xoops->tpl()->assign('poster', $poster);
        $avatar = $xoops->service('avatar')->getAvatarUrl($poster)->getValue();
        $xoops->tpl()->assign('poster_avatar', $avatar);
    }

    if ($pm->getVar("to_userid") == $xoops->user->getVar("uid") && $pm->getVar('read_msg') == 0) {
        $pm_handler->setRead($pm);
    }

    $message = $pm->getValues();
    $message['msg_time'] = XoopsLocale::formatTimestamp($pm->getVar("msg_time"));
}
$xoops->tpl()->assign('message', $message);
$xoops->tpl()->assign('op', $_REQUEST['op']);
$xoops->tpl()->assign('previous', $start - 1);
$xoops->tpl()->assign('next', $start + 1);
$xoops->tpl()->assign('total_messages', $total_messages);

$xoops->footer();

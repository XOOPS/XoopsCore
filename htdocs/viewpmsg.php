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
 * XOOPS message detail
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->preload()->triggerEvent('core.viewpmsg.start');

if (!$xoops->isUser()) {
    $errormessage = XoopsLocale::E_YOU_ARE_NOT_REGISTERED . "<br />"
        . XoopsLocale::E_REGISTER_FIRST_TO_SEND_PRIVATE_MESSAGES . "";
    $xoops->redirect("user.php", 2, $errormessage);
} else {
    $pm_handler = $xoops->getHandlerPrivmessage();
    if (isset($_POST['delete_messages']) && (isset($_POST['msg_id']) || isset($_POST['msg_ids']))) {
        if (!$xoops->security()->check()) {
            echo implode('<br />', $xoops->security()->getErrors());
            exit();
        } else {
            if (empty($_REQUEST['ok'])) {
                $xoops->header('module:system/system_viewpmsg.tpl');
                // Define Stylesheet
                $xoops->theme()->addStylesheet('modules/system/css/admin.css');
                $xoops->confirm(array(
                        'ok' => 1, 'delete_messages' => 1,
                        'msg_ids' => json_encode(array_map("intval", $_POST['msg_id']))
                    ), $_SERVER['REQUEST_URI'], XoopsLocale::Q_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MESSAGES);
                $xoops->footer();
            }
        }

        $clean_msg_id = json_decode($_POST['msg_ids'], true, 2);
        if (!empty($clean_msg_id)) {
            $clean_msg_id = array_map("intval", $clean_msg_id);
        }
        $size = count($clean_msg_id);
        $msg =& $clean_msg_id;
        for ($i = 0; $i < $size; $i++) {
            $pm = $pm_handler->get(intval($msg[$i]));
            if ($pm->getVar('to_userid') == $xoops->user->getVar('uid')) {
                $pm_handler->delete($pm);
            }
            unset($pm);
        }
        $xoops->redirect("viewpmsg.php", 1, XoopsLocale::S_YOUR_MESSAGES_DELETED);
    }
    $xoops->header('module:system/system_viewpmsg.tpl');
    $criteria = new Criteria('to_userid', $xoops->user->getVar('uid'));
    $criteria->setSort('msg_time');
    $criteria->setOrder('DESC');
    $pm_arr = $pm_handler->getObjects($criteria);
    $total_messages = count($pm_arr);
    $xoops->tpl()->assign('display', true);
    $xoops->tpl()->assign('anonymous', $xoops->getConfig('anonymous'));
    $xoops->tpl()->assign('uid', $xoops->user->getVar("uid"));
    $xoops->tpl()->assign('total_messages', $total_messages);
    $msg_no = 0;
    foreach (array_keys($pm_arr) as $i) {
        $messages['msg_id'] = $pm_arr[$i]->getVar("msg_id");
        $messages['read_msg'] = $pm_arr[$i]->getVar("read_msg");
        $messages['msg_image'] = $pm_arr[$i]->getVar("msg_image");
        $messages['posteruid'] = $pm_arr[$i]->getVar('from_userid');
        $messages['postername'] = XoopsUser::getUnameFromId($pm_arr[$i]->getVar('from_userid'));
        $messages['subject'] = $pm_arr[$i]->getVar("subject");
        $messages['msg_time'] = XoopsLocale::formatTimestamp($pm_arr[$i]->getVar('msg_time'));
        $messages['msg_no'] = $msg_no;
        $xoops->tpl()->append('messages', $messages);
        $msg_no++;
    }
    $xoops->tpl()->assign('token', $xoops->security()->getTokenHTML());
    $xoops->footer();
}

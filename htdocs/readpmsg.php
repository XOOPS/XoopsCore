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
 * XOOPS message list
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
$xoops = Xoops::getInstance();
$xoops->preload()->triggerEvent('core.readpmsg.start');

if (!$xoops->isUser()) {
    $xoops->redirect("user.php", 2, XoopsLocale::E_YOU_ARE_NOT_REGISTERED);
}

$op = Request::getCmd('op', 'list');
$id = Request::getInt('msg_id', 0);
$start = Request::getInt('start', 0);
$total_messages = Request::getInt('total_messages', 0);

$pm_handler = $xoops->getHandlerPrivmessage();

$xoops->header('module:system/system_readpmsg.tpl');

switch ($op) {

    case 'list':
    default:

        $criteria = new CriteriaCompo(new Criteria('to_userid', $xoops->user->getVar('uid')));
        if ($id > 0) {
            $criteria->add(new Criteria('msg_id', $id));
        } else {
            $criteria->setStart($start);
        }
        $criteria->setLimit(1);
        $criteria->setSort('msg_time');
        $criteria->setOrder('DESC');
        $pm_arr = $pm_handler->getObjects($criteria);
        $xoops->tpl()->assign('uid', $xoops->user->getVar("uid"));
        if (empty($pm_arr)) {
            $xoops->tpl()->assign('error_msg', $xoops->alert('error', XoopsLocale::E_YOU_DO_NOT_HAVE_ANY_PRIVATE_MESSAGE));
        } else {
            if ($pm_arr[0]->getVar('read_msg') == 0) {
                $pm_handler->setRead($pm_arr[0]);
            }
            $poster = new XoopsUser($pm_arr[0]->getVar("from_userid"));
            if (!is_object($poster)) {
                $xoops->tpl()->assign('poster', false);
                $xoops->tpl()->assign('anonymous', $xoopsConfig['anonymous']);
            } else {
                $xoops->tpl()->assign('poster', $poster);
                $avatar = $xoops->service('avatar')->getAvatarUrl($poster)->getValue();
                $xoops->tpl()->assign('poster_avatar', $avatar);
            }
            $xoops->tpl()->assign('msg_id', $pm_arr[0]->getVar("msg_id"));
            $xoops->tpl()->assign('subject', $pm_arr[0]->getVar("subject"));
            $xoops->tpl()->assign('msg_time', XoopsLocale::formatTimestamp($pm_arr[0]->getVar("msg_time")));
            $xoops->tpl()->assign('msg_image', $pm_arr[0]->getVar("msg_image", "E"));
            $xoops->tpl()->assign('msg_text', $pm_arr[0]->getVar("msg_text"));
            $xoops->tpl()->assign('previous', $start - 1);
            $xoops->tpl()->assign('next', $start + 1);
            $xoops->tpl()->assign('total_messages', $total_messages);
            $xoops->tpl()->assign('read', true);
            $xoops->tpl()->assign('token', $xoops->security()->getTokenHTML());
        }
        break;

    case 'delete':
        $obj = $pm_handler->get($id);
        if (isset($_POST["ok"]) && $_POST["ok"] == 1) {
            if (!$xoops->security()->check()) {
                $xoops->redirect("viewpmsg.php", 3, implode(",", $xoops->security()->getErrors()));
            }
            if ($pm_handler->delete($obj)) {
                $xoops->redirect("viewpmsg.php", 2, XoopsLocale::S_YOUR_MESSAGES_DELETED);
            } else {
                echo $xoops->alert('error', $obj->getHtmlErrors());
            }
        } else {
            $xoops->tpl()->assign('subject', $obj->getVar("subject"));
            $xoops->confirm(array("ok" => 1, "msg_id" => $id, "op" => "delete"), 'readpmsg.php', XoopsLocale::Q_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_MESSAGES . '<br />' . $obj->getVar("subject"));
        }
        break;
}
$xoops->footer();

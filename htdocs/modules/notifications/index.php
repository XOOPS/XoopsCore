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
 * XOOPS notification
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$helper = Notifications::getInstance();

if (!$xoops->isUser()) {
    $xoops->redirect('index.php', 3, _MD_NOTIFICATIONS_NOACCESS);
}

$uid = $xoops->user->getVar('uid');

$op = 'list';
if (isset($_POST['op'])) {
    $op = trim($_POST['op']);
} else {
    if (isset($_GET['op'])) {
        $op = trim($_GET['op']);
    }
}
if (isset($_POST['delete'])) {
    $op = 'delete';
} else {
    if (isset($_GET['delete'])) {
        $op = 'delete';
    }
}
if (isset($_POST['delete_ok'])) {
    $op = 'delete_ok';
}
if (isset($_POST['delete_cancel'])) {
    $op = 'cancel';
}

switch ($op) {
    case 'cancel':
        // FIXME: does this always go back to correct location??
        $xoops->redirect('index.php');
        break;

    case 'list':
        // Do we allow other users to see our notifications?  Nope, but maybe
        // see who else is monitoring a particular item (or at least how many)?
        // Well, maybe admin can see all...
        // TODO: need to span over multiple pages...???
        // Get an array of all notifications for the selected user
        $criteria = new Criteria('uid', $uid);
        $criteria->setSort('modid,category,itemid');
        $notification_handler = $helper->getHandlerNotification();
        $notifications = $notification_handler->getObjectsArray($criteria);

        // Generate the info for the template
        $module_handler = $xoops->getHandlerModule();
        $modules = array();
        $prev_modid = -1;
        $prev_category = -1;
        $prev_item = -1;
        $modulesObj = array();
        foreach ($notifications as $n) {
            /* @var $n NotificationsNotification */
            $modid = $n->getVar('modid');
            if ($modid != $prev_modid) {
                $prev_modid = $modid;
                $prev_category = -1;
                $prev_item = -1;
                $module = $xoops->getModuleById($modid);
                $modulesObj[$modid] = $module;
                $modules[$modid] = array(
                    'id' => $modid, 'name' => $module->getVar('name'), 'categories' => array()
                );
                // TODO: note, we could auto-generate the url from the id
                // and category info... (except when category has multiple
                // subscription scripts defined...)
                // OR, add one more option to xoops_version 'view_from'
                // which tells us where to redirect... BUT, e.g. forums, it
                // still wouldn't give us all the required info... e.g. the
                // topic ID doesn't give us the ID of the forum which is
                // a required argument...
                // Get the lookup function, if exists
            }
            $category = $n->getVar('category');
            if ($category != $prev_category) {
                $category_info = array();
                $prev_category = $category;
                $prev_item = -1;
                $category_info = $helper->getCategory($category, $modulesObj[$modid]->getVar('dirname'));
                $modules[$modid]['categories'][$category] = array(
                    'name' => $category, 'title' => $category_info['title'], 'items' => array()
                );
            }
            $item = $n->getVar('itemid');
            if ($item != $prev_item) {
                $prev_item = $item;

                $item_info = $helper->getItem($category, $item, $modulesObj[$modid]->getVar('dirname'));
                $modules[$modid]['categories'][$category]['items'][$item] = array(
                    'id' => $item, 'name' => $item_info['name'], 'url' => $item_info['url'], 'notifications' => array()
                );
            }
            $event_info = $helper->getEvent($category, $n->getVar('event'), $modulesObj[$n->getVar('modid')]->getVar('dirname'));
            $modules[$modid]['categories'][$category]['items'][$item]['notifications'][] = array(
                'id'             => $n->getVar('id'), 'module_id' => $n->getVar('modid'),
                'category'       => $n->getVar('category'), 'category_title' => $category_info['title'],
                'item_id'        => $n->getVar('itemid'), 'event' => $n->getVar('event'),
                'event_title'    => $event_info['title'], 'user_id' => $n->getVar('uid')
            );
        }
        $xoops->header('module:notifications/list.tpl');
        $xoops->tpl()->assign('modules', $modules);
        $user_info = array('uid' => $xoops->user->getVar('uid'));
        $xoops->tpl()->assign('user', $user_info);
        $xoops->tpl()->assign('lang_cancel', XoopsLocale::A_CANCEL);
        $xoops->tpl()->assign('lang_clear', _MD_NOTIFICATIONS_CLEAR);
        $xoops->tpl()->assign('lang_delete', XoopsLocale::A_DELETE);
        $xoops->tpl()->assign('lang_checkall', _MD_NOTIFICATIONS_CHECKALL);
        $xoops->tpl()->assign('lang_module', _MD_NOTIFICATIONS_MODULE);
        $xoops->tpl()->assign('lang_event', _MD_NOTIFICATIONS_EVENT);
        $xoops->tpl()->assign('lang_events', _MD_NOTIFICATIONS_EVENTS);
        $xoops->tpl()->assign('lang_category', _MD_NOTIFICATIONS_CATEGORY);
        $xoops->tpl()->assign('lang_itemid', _MD_NOTIFICATIONS_ITEMID);
        $xoops->tpl()->assign('lang_itemname', _MD_NOTIFICATIONS_ITEMNAME);
        $xoops->tpl()->assign('lang_activenotifications', _MD_NOTIFICATIONS_ACTIVENOTIFICATIONS);
        $xoops->tpl()->assign('notification_token', $xoops->security()->createToken());
        $xoops->footer();

        // TODO: another display mode... instead of one notification per line,
        // show one line per item_id, with checkboxes for the available options...
        // and an update button to change them...  And still have the delete box
        // to delete all notification for that item
        // How about one line per ID, showing category, name, id, and list of
        // events...
        // TODO: it would also be useful to provide links to other available
        // options so we can say switch from new_message to 'bookmark' if we
        // are receiving too many emails.  OR, if we click on 'change options'
        // we get a form for that page...
        // TODO: option to specify one-time??? or other modes??
        break;

    case 'delete_ok':
        if (empty($_POST['del_not'])) {
            $helper->redirect('index.php', 2, _MD_NOTIFICATIONS_NOTHINGTODELETE);
        }
        $xoops->header();
        $hidden_vars = array(
            'uid' => $uid, 'delete_ok' => 1, 'del_not' => $_POST['del_not']
        );
        echo '<h4>' . _MD_NOTIFICATIONS_DELETINGNOTIFICATIONS . '</h4>';
        $xoops->confirm($hidden_vars, $xoops->getEnv('PHP_SELF'), _MD_NOTIFICATIONS_RUSUREDEL);
        $xoops->footer();
        // FIXME: There is a problem here... in $xoops->confirm it treats arrays as
        // optional radio arguments on the confirmation page... change this or
        // write new function...
        break;

    case 'delete':
        if (!$xoops->security()->check()) {
            $helper->redirect('index.php', 2, implode('<br />', $xoops->security()->getErrors()));
        }
        if (empty($_POST['del_not'])) {
            $helper->redirect('index.php', 2, _MD_NOTIFICATIONS_NOTHINGTODELETE);
        }
        $notification_handler = $helper->getHandlerNotification();
        foreach ($_POST['del_not'] as $n_array) {
            foreach ($n_array as $n) {
                $notification = $notification_handler->get($n);
                if ($notification->getVar('uid') == $uid) {
                    $notification_handler->delete($notification);
                }
            }
        }
        $helper->redirect('index.php', 2, _MD_NOTIFICATIONS_DELETESUCCESS);
        break;
    default:
        break;
}

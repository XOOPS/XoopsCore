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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

function b_notification_show()
{
    $xoops = Xoops::getInstance();
    $helper = Notifications::getInstance();
    include_once $helper->path('include/notification_functions.php');

    $helper->loadLanguage('main');
    // Notification must be enabled, and user must be logged in
    if (!$xoops->isUser() || !notificationEnabled('block')) {
        return false; // do not display block
    }
    $notification_handler = $helper->getHandlerNotification();
    // Now build the a nested associative array of info to pass
    // to the block template.
    $block = array();
    $categories = notificationSubscribableCategoryInfo();
    if (empty($categories)) {
        return false;
    }
    foreach ($categories as $category) {
        $section['name'] = $category['name'];
        $section['title'] = $category['title'];
        $section['description'] = $category['description'];
        $section['itemid'] = $category['item_id'];
        $section['events'] = array();
        $subscribed_events = $notification_handler->getSubscribedEvents($category['name'], $category['item_id'], $xoops->module->getVar('mid'), $xoops->user->getVar('uid'));
        foreach (notificationEvents($category['name'], true) as $event) {
            if (!empty($event['admin_only']) && !$xoops->user->isAdmin($xoops->module->getVar('mid'))) {
                continue;
            }
            $subscribed = in_array($event['name'], $subscribed_events) ? 1 : 0;
            $section['events'][$event['name']] = array(
                'name' => $event['name'], 'title' => $event['title'], 'caption' => $event['caption'],
                'description' => $event['description'], 'subscribed' => $subscribed
            );
        }
        $block['categories'][$category['name']] = $section;
    }
    // Additional form data
    $block['target_page'] = "notification_update.php";
    // FIXME: better or more standardized way to do this?
    $script_url = explode('/', $_SERVER['PHP_SELF']);
    $script_name = $script_url[count($script_url) - 1];
    $block['redirect_script'] = $script_name;
    $block['submit_button'] = _MD_NOTIFICATIONS_UPDATENOW;
    $block['notification_token'] = $xoops->security()->createToken();
    return $block;
}

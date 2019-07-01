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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
define('_MD_NOTIFICATIONS', 'Notifications');
define('_MD_NOTIFICATIONS_NOTIFICATIONOPTIONS', 'Notification Options');
define('_MD_NOTIFICATIONS_UPDATENOW', 'Update Now');
define('_MD_NOTIFICATIONS_UPDATEOPTIONS', 'Update Notification Options');
define('_MD_NOTIFICATIONS_CLEAR', 'Clear');
define('_MD_NOTIFICATIONS_CHECKALL', 'Check All');
define('_MD_NOTIFICATIONS_MODULE', 'Module');
define('_MD_NOTIFICATIONS_CATEGORY', 'Category');
define('_MD_NOTIFICATIONS_ITEMID', 'ID');
define('_MD_NOTIFICATIONS_ITEMNAME', 'Name');
define('_MD_NOTIFICATIONS_EVENT', 'Event');
define('_MD_NOTIFICATIONS_EVENTS', 'Events');
define('_MD_NOTIFICATIONS_ACTIVENOTIFICATIONS', 'Active Notifications');
//define('_MD_NOTIFICATIONS_NAMENOTAVAILABLE', 'Name Not Available');
// RMV-NEW : TODO: remove NAMENOTAVAILBLE above
define('_MD_NOTIFICATIONS_ITEMNAMENOTAVAILABLE', 'Item Name Not Available');
define('_MD_NOTIFICATIONS_ITEMTYPENOTAVAILABLE', 'Item Type Not Available');
define('_MD_NOTIFICATIONS_ITEMURLNOTAVAILABLE', 'Item URL Not Available');
define('_MD_NOTIFICATIONS_DELETINGNOTIFICATIONS', 'Deleting Notifications');
define('_MD_NOTIFICATIONS_DELETESUCCESS', 'Notification(s) deleted successfully.');
define('_MD_NOTIFICATIONS_UPDATEOK', 'Notification options updated');
define('_MD_NOTIFICATIONS_NOTIFICATIONMETHODIS', 'Notification method is');
define('_MD_NOTIFICATIONS_EMAIL', 'email');
define('_MD_NOTIFICATIONS_PM', 'private message');
define('_MD_NOTIFICATIONS_DISABLE', 'disabled');
define('_MD_NOTIFICATIONS_CHANGE', 'Change');
define('_MD_NOTIFICATIONS_NOACCESS', 'You do not have permission to access this page.');
// Text for module config options
//define('_MD_NOTIFICATIONS_ENABLE', 'Enable');
define('_MD_NOTIFICATIONS_NOTIFICATION', 'Notification');
define('_MD_NOTIFICATIONS_CONFIG_ENABLED', 'Enable Notification');
//define('_MD_NOTIFICATIONS_CONFIG_ENABLEDDSC', 'This module allows users to select to be notified when certain events occur.  Choose "yes" to enable this feature.');
define('_MD_NOTIFICATIONS_CONFIG_EVENTS', 'Enable Specific Events');
define('_MD_NOTIFICATIONS_CONFIG_EVENTSDSC', 'Select which notification events to which your users may subscribe.');
define('_MD_NOTIFICATIONS_CONFIG_ENABLE', 'Enable Notification');
define('_MD_NOTIFICATIONS_CONFIG_ENABLEDSC', 'This module allows users to be notified when certain events occur.  Select if users should be presented with notification options in a Block (Block-style), within the module (Inline-style), or both.  For block-style notification, the Notification Options block must be enabled for this module.');
define('_MD_NOTIFICATIONS_CONFIG_DISABLE', 'Disable Notification');
define('_MD_NOTIFICATIONS_CONFIG_ENABLEBLOCK', 'Enable only Block-style');
define('_MD_NOTIFICATIONS_CONFIG_ENABLEINLINE', 'Enable only Inline-style');
define('_MD_NOTIFICATIONS_CONFIG_ENABLEBOTH', 'Enable Notification (both styles)');
// For notification about comment events
define('_MD_NOTIFICATIONS_COMMENT_NOTIFY', 'Comment Added');
define('_MD_NOTIFICATIONS_COMMENT_NOTIFYCAP', 'Notify me when a new comment is posted for this item.');
define('_MD_NOTIFICATIONS_COMMENT_NOTIFYDSC', 'Receive notification whenever a new comment is posted (or approved) for this item.');
define('_MD_NOTIFICATIONS_COMMENT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify: Comment added to {X_ITEM_TYPE}');
define('_MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFY', 'Comment Submitted');
define('_MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFYCAP', 'Notify me when a new comment is submitted (awaiting approval) for this item.');
define('_MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFYDSC', 'Receive notification whenever a new comment is submitted (awaiting approval) for this item.');
define('_MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify: Comment submitted for {X_ITEM_TYPE}');
// For notification bookmark feature
// (Not really notification, but easy to do with this module)
define('_MD_NOTIFICATIONS_BOOKMARK_NOTIFY', 'Bookmark');
define('_MD_NOTIFICATIONS_BOOKMARK_NOTIFYCAP', 'Bookmark this item (no notification).');
define('_MD_NOTIFICATIONS_BOOKMARK_NOTIFYDSC', 'Keep track of this item without receiving any event notifications.');
// For user profile
// FIXME: These should be reworded a little...
//define('_MD_NOTIFICATIONS_NOTIFYMETHOD', 'Notification Method<br /> When you monitor e.g. a forum, how would you like to receive notifications of updates?');
//define('_MD_NOTIFICATIONS_METHOD_EMAIL', 'Email (use address in my profile)');
//define('_MD_NOTIFICATIONS_METHOD_PM', 'Private Message');
//define('_MD_NOTIFICATIONS_METHOD_DISABLE', 'Temporarily Disable');
//define('_MD_NOTIFICATIONS_NOTIFYMODE', 'Default Notification Mode');
//define('_MD_NOTIFICATIONS_MODE_SENDALWAYS', 'Notify me of all selected updates');
//define('_MD_NOTIFICATIONS_MODE_SENDONCE', 'Notify me only once');
//define('_MD_NOTIFICATIONS_MODE_SENDONCEPERLOGIN', 'Notify me once then disable until I log in again');
define('_MD_NOTIFICATIONS_NOTHINGTODELETE', 'There is nothing to delete.');

//added on 2.6.0
define('_MD_NOTIFICATIONS_RUSUREDEL', 'Are you sure you want to delete this notification?');

define('_MD_NOTIFICATIONS_DELETE', 'Deleting notifications...');
define('_MD_NOTIFICATIONS_DELETE_ERROR', 'ERROR: Could not delete notifications');
define('_MD_NOTIFICATIONS_DELETED', 'Notifications deleted');

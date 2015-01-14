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

interface NotificationsPluginInterface
{
    /**
     * @param string $category
     * @param int    $itemId
     * expects an array containing:
     * name,      Name of the Item
     * url,       Url of the Item
     *
     * @return array
     */
    public function item($category, $itemId);

    /**
     * Expects array of arrays containing:
     *
     * 'name' => 'thread';
     * 'title' => _MI_NEWBB_THREAD_NOTIFY;
     * 'description' => _MI_NEWBB_THREAD_NOTIFYDSC;
     * 'subscribe_from' => 'viewtopic.php';
     * 'item_name' => 'topic_id';
     * 'allow_bookmark' => 1;
     *
     * name: the name of the category
     * title: the title of the category (use language strings)
     * description: a description of the category (use language strings)
     * subscribe_from: an array of all scripts where user is permitted to subscribe(Use '*' for all scripts. Omit this line for no scripts.
     *                 Note: you would specify no scripts only if your module provides other ways for users to subscribe, e.g. custom checkboxes within forms.)
     * item_name: the HTTP //get// parameter to watch, which specifies the ID of the specific item in the category (e.g. forum ID or thread ID).
     *            If set, the //get// parameter must be submitted via HTTP call, otherwise, the notification categories remain hidden.
     * allow_bookmark: set to 1 if you want the system to allow this item to be bookmarked by users
     *
     * @return array
     */
    public function categories();

    /**
     * Expects array of arrays containing:
     *
     * 'name' => 'new_post';
     * 'category' => 'thread';
     * 'title' => _MI_NEWBB_THREAD_NEWPOST_NOTIFY;
     * 'caption' => _MI_NEWBB_THREAD_NEWPOST_NOTIFYCAP;
     * 'description' => _MI_NEWBB_THREAD_NEWPOST_NOTIFYDSC;
     * 'mail_template' => 'thread_newpost_notify';
     * 'mail_subject' => _MI_NEWBB_THREAD_NEWPOST_NOTIFYSBJ;
     *
     * name: the name of the event
     * category: the category of the event
     * title: title of event (use language strings)
     * caption: description in form "Notify me when..." (use language strings)
     * description: description of event (use language strings)
     * mail_template: mail template in ##language/<language>/mail_template## directory of module (omit the '.tpl' suffix)
     * mail_subject: subject of email (use language strings)
     *
     * The following are optional:
     * admin_only: set to 1 if you wish the event to be visible only to module administrators
     * invisible: set to 1 if you wish the event to be invisible... i.e. won't show up in module preferences or in notification blocks.
     *            It is used for 'custom' notifications: e.g. in 'mylinks', you can sign up (on the submit form)
     *            for a one-time notification when your link submission is approved. The 'approve' event is invisible.
     *
     * @return array
     */
    public function events();

    /**
     * Expects array containing tags to use in mail template
     *
     * ex: return array('X_SOME_NEW_TAG' => 'Some New Content');
     * note: Using tags is optional, you can return an empty array if you like
     *
     * @param string $category
     * @param int $item_id
     * @param string $event
     *
     * @return mixed
     */
    public function tags($category, $item_id, $event);
}


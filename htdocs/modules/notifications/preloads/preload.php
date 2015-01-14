<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\PreloadItem;

/**
 * Notifications core preloads
 *
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class NotificationsPreload extends PreloadItem
{
    /**
     * listen for core.include.common.classmaps
     * add any module specific class map entries
     *
     * @param mixed $args not used
     *
     * @return void
     */
    public static function eventCoreIncludeCommonClassmaps($args)
    {
        $path = dirname(__DIR__);
        XoopsLoad::addMap(array(
            'notifications' => $path . '/class/helper.php',
        ));
    }

    public static function eventCoreFooterStart($args)
    {
        $xoops = Xoops::getInstance();
        $helper = Notifications::getInstance();

        $notifications = array();
        $notifications['show'] = $xoops->isModule() && $xoops->isUser() && $helper->enabled('inline') ? 1 : 0;
        if ($notifications['show']) {
            $helper->loadLanguage('main');
            $categories = $helper->getSubscribableCategories();
            $event_count = 0;
            if (!empty($categories)) {
                $notification_handler = $helper->getHandlerNotification();
                foreach ($categories as $category) {
                    $section['name'] = $category['name'];
                    $section['title'] = $category['title'];
                    $section['description'] = $category['description'];
                    $section['itemid'] = $category['item_id'];
                    $section['events'] = array();
                    $subscribed_events = $notification_handler->getSubscribedEvents($category['name'], $category['item_id'], $xoops->module->getVar('mid'), $xoops->user->getVar('uid'));
                    foreach ($helper->getEvents($category['name'], true, $xoops->module->getVar('dirname')) as $event) {
                        if (!empty($event['admin_only']) && !$xoops->user->isAdmin($xoops->module->getVar('mid'))) {
                            continue;
                        }
                        if (!empty($event['invisible'])) {
                            continue;
                        }
                        $subscribed = in_array($event['name'], $subscribed_events) ? 1 : 0;
                        $section['events'][$event['name']] = array(
                            'name'        => $event['name'],
                            'title'       => $event['title'],
                            'caption'     => $event['caption'],
                            'description' => $event['description'],
                            'subscribed'  => $subscribed
                        );
                        $event_count++;
                    }
                    $notifications['categories'][$category['name']] = $section;
                }
                $notifications['target_page'] = $helper->url('update.php');
                $notifications['mid'] = $xoops->module->getVar('mid');
                $notifications['redirect_script'] = $xoops->getEnv('PHP_SELF');
                $xoops->tpl()->assign(array(
                    'lang_activenotifications'  => _MD_NOTIFICATIONS_ACTIVENOTIFICATIONS,
                    'lang_notificationoptions'  => _MD_NOTIFICATIONS_NOTIFICATIONOPTIONS,
                    'lang_updateoptions'        => _MD_NOTIFICATIONS_UPDATEOPTIONS,
                    'lang_updatenow'            => _MD_NOTIFICATIONS_UPDATENOW,
                    'lang_category'             => _MD_NOTIFICATIONS_CATEGORY,
                    'lang_event'                => _MD_NOTIFICATIONS_EVENT,
                    'lang_events'               => _MD_NOTIFICATIONS_EVENTS,
                    'lang_checkall'             => _MD_NOTIFICATIONS_CHECKALL,
                    'lang_notificationmethodis' => _MD_NOTIFICATIONS_NOTIFICATIONMETHODIS,
                    'lang_change'               => _MD_NOTIFICATIONS_CHANGE,
                    'editprofile_url'           => XOOPS_URL . '/edituser.php?uid=' . $xoops->user->getVar('uid')
                ));
                switch ($xoops->user->getVar('notify_method')) {
                    case NOTIFICATIONS_METHOD_DISABLE:
                        $xoops->tpl()->assign('user_method', _MD_NOTIFICATIONS_DISABLE);
                        break;
                    case NOTIFICATIONS_METHOD_PM:
                        $xoops->tpl()->assign('user_method', _MD_NOTIFICATIONS_PM);
                        break;
                    case NOTIFICATIONS_METHOD_EMAIL:
                        $xoops->tpl()->assign('user_method', _MD_NOTIFICATIONS_EMAIL);
                        break;
                }
            } else {
                $notifications['show'] = 0;
            }
            if ($event_count == 0) {
                $notifications['show'] = 0;
            }
        }
        $xoops->tpl()->assign('notifications', $notifications);
    }

    public static function eventOnModuleUpdateConfigs($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        $configs =& $args[1];
        $helper = Notifications::getInstance(); //init helper to load defines na language

        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'notifications', true)) {
            $notConfigs = $helper->getPluginableConfigs($module);
            foreach ($notConfigs as $notConfig) {
                array_push($configs, $notConfig);
            }
        }
    }

    public static function eventOnModuleInstallConfigs($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'notifications', true)) {
            Notifications::getInstance()->insertModuleRelations($module);
        }
    }

    public static function eventOnModuleUnistall($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'notifications')) {
            Notifications::getInstance()->deleteModuleRelations($module);
        }
    }

    public static function eventOnSystemPreferencesForm($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'notifications')) {
            Notifications::getInstance()->loadLanguage('main');
        }
    }
}

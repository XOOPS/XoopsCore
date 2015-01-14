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

class Notifications extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('notifications');
        define('NOTIFICATIONS_MODE_SENDALWAYS', 0);
        define('NOTIFICATIONS_MODE_SENDONCETHENDELETE', 1);
        define('NOTIFICATIONS_MODE_SENDONCETHENWAIT', 2);
        define('NOTIFICATIONS_MODE_WAITFORLOGIN', 3);

        define('NOTIFICATIONS_METHOD_DISABLE', 0);
        define('NOTIFICATIONS_METHOD_PM', 1);
        define('NOTIFICATIONS_METHOD_EMAIL', 2);

        define('NOTIFICATIONS_DISABLE', 0);
        define('NOTIFICATIONS_ENABLEBLOCK', 1);
        define('NOTIFICATIONS_ENABLEINLINE', 2);
        define('NOTIFICATIONS_ENABLEBOTH', 3);
    }

    /**
     * @return string
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return NotificationsNotificationHandler
     */
    public function getHandlerNotification()
    {
        return $this->getHandler('notification');
    }

    /**
     * @param string       $style
     * @param null|string  $module_dirname
     *
     * @return bool
     */
    public function enabled($style, $module_dirname = null)
    {
        $xoops = Xoops::getInstance();
        if ($status = $xoops->getModuleConfig('notifications_enabled')) {
        } else {
            if (!isset($module_dirname)) {
                return false;
            }

            if (!$status = $xoops->getModuleConfig('notifications_enabled', $module_dirname)) {
                return false;
            }
        }
        if (($style == 'block') && ($status == NOTIFICATIONS_ENABLEBLOCK || $status == NOTIFICATIONS_ENABLEBOTH)) {
            return true;
        }
        if (($style == 'inline') && ($status == NOTIFICATIONS_ENABLEINLINE || $status == NOTIFICATIONS_ENABLEBOTH)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $category
     * @param int    $item_id
     * @param string $dirname Module dirname
     *
     * @return array!bool
     */
    public function getItem($category, $item_id, $dirname = null)
    {
        $xoops = Xoops::getInstance();
        if (!isset($dirname)) {
            $dirname = $xoops->module->getVar('dirname');
        }

        /* @var $plugin NotificationsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'notifications')) {
            return $plugin->item($category, intval($item_id));
        }
        return false;
    }

    /**
     * @param string $category
     * @param int    $item_id
     * @param string $event
     * @param string $dirname Module dirname
     *
     * @return array!bool
     */
    public function getTags($category, $item_id, $event, $dirname = null)
    {
        $xoops = Xoops::getInstance();
        if (!isset($dirname)) {
            $dirname = $xoops->module->getVar('dirname');
        }

        /* @var $plugin NotificationsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'notifications')) {
            return $plugin->tags($category, intval($item_id), $event, $dirname);
        }
        return array();
    }

    /**
     * Get an associative array of info for a particular notification
     * category in the selected module.  If no category is selected,
     * return an array of info for all categories.
     *
     * @param string $category_name Category name (default all categories)
     * @param string $dirname       ID of the module (default current module)
     *
     * @return mixed
     */
    public function getCategory($category_name = '', $dirname = null)
    {
        $xoops = Xoops::getInstance();
        if (!isset($dirname)) {
            $dirname = $xoops->module->getVar('dirname');
        }

        /* @var $plugin NotificationsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'notifications')) {
            $categories = $plugin->categories();
            if (empty($category_name)) {
                return $categories;
            }
            foreach ($categories as $category) {
                if ($category['name'] == $category_name) {
                    return $category;
                }
            }
        }
        return false;
    }

    /**
     * Get associative array of info for the category to which comment events
     * belong.
     *
     * todo, this belongs in the comment module no? - trabis
     *
     * @param string $dirname Dirname of the module (default current module)
     *
     * @return mixed            Associative array of category info
     */
    public function getCommentsCategory($dirname = null)
    {
        $ret = array();
        $all_categories = $this->getCategory('', $dirname);
        if (empty($all_categories)) {
            return $ret;
        }
        foreach ($all_categories as $category) {
            $all_events = $this->getEvents($category['name'], false, $dirname);
            if (empty($all_events)) {
                continue;
            }
            foreach ($all_events as $event) {
                if ($event['name'] == 'comment') {
                    return $category;
                }
            }
        }
        return $ret;
    }

    // TODO: some way to include or exclude admin-only events...

    /**
     * Get an array of info for all events (each event has associative array)
     * in the selected category of the selected module.
     *
     * @param string $category_name Category name
     * @param bool   $enabled_only  If true, return only enabled events
     * @param string $dirname       Dirname of the module (default current module)
     *
     * @return mixed
     */
    public function getEvents($category_name, $enabled_only, $dirname = null)
    {
        $xoops = Xoops::getInstance();
        $helper = Notifications::getInstance();

        if (!isset($dirname)) {
            $dirname = $xoops->isModule() ? $xoops->module->getVar('dirname') : '';
        }
        /* @var $plugin NotificationsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'notifications')) {

            $events = $plugin->events();

            $category = $this->getCategory($category_name, $dirname);

            $event_array = array();

            $override_comment = false;
            $override_commentsubmit = false;
            $override_bookmark = false;

            foreach ($events as $event) {
                if ($event['category'] == $category_name) {
                    if (!is_dir($dir = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/locale/' . $xoops->getConfig('locale') . '/templates/')) {
                        $dir = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/locale/en_US/templates/';
                    }
                    $event['mail_template_dir'] = $dir;
                    if (!$enabled_only || $this->eventEnabled($category, $event, $dirname)) {
                        $event_array[] = $event;
                    }
                    if ($event['name'] == 'comment') {
                        $override_comment = true;
                    }
                    if ($event['name'] == 'comment_submit') {
                        $override_commentsubmit = true;
                    }
                    if ($event['name'] == 'bookmark') {
                        $override_bookmark = true;
                    }
                }
            }

            $helper->loadLanguage('main');
            // Insert comment info if applicable

            /* @var $commentsPlugin CommentsPluginInterface */
            if ($xoops->isActiveModule('comments') && $commentsPlugin = \Xoops\Module\Plugin::getPlugin($dirname, 'comments')) {
                //todo replace this
                if (!empty($category['item_name']) && $category['item_name'] == $commentsPlugin->itemName()) {
                    if (!is_dir($dir = XOOPS_ROOT_PATH . '/locale/' . $xoops->getConfig('locale') . '/templates/')) {
                        $dir = XOOPS_ROOT_PATH . '/locale/en_US/templates/';
                    }
                    $mail_template_dir = $dir;

                    $com_config = $xoops->getModuleConfigs($dirname);
                    if (!$enabled_only) {
                        $insert_comment = true;
                        $insert_submit = true;
                    } else {
                        $insert_comment = false;
                        $insert_submit = false;
                        switch ($com_config['com_rule']) {
                            case COMMENTS_APPROVENONE:
                                // comments disabled, no comment events
                                break;
                            case COMMENTS_APPROVEALL:
                                // all comments are automatically approved, no 'submit'
                                if (!$override_comment) {
                                    $insert_comment = true;
                                }
                                break;
                            case COMMENTS_APPROVEUSER:
                            case COMMENTS_APPROVEADMIN:
                                // comments first submitted, require later approval
                                if (!$override_comment) {
                                    $insert_comment = true;
                                }
                                if (!$override_commentsubmit) {
                                    $insert_submit = true;
                                }
                                break;
                        }
                    }
                    if ($insert_comment) {
                        $event = array(
                            'name'              => 'comment',
                            'category'          => $category['name'],
                            'title'             => _MD_NOTIFICATIONS_COMMENT_NOTIFY,
                            'caption'           => _MD_NOTIFICATIONS_COMMENT_NOTIFYCAP,
                            'description'       => _MD_NOTIFICATIONS_COMMENT_NOTIFYDSC,
                            'mail_template_dir' => $mail_template_dir,
                            'mail_template'     => 'comment_notify',
                            'mail_subject'      => _MD_NOTIFICATIONS_COMMENT_NOTIFYSBJ
                        );
                        if (!$enabled_only || $this->eventEnabled($category, $event, $dirname)) {
                            $event_array[] = $event;
                        }
                    }
                    if ($insert_submit) {
                        $event = array(
                            'name'              => 'comment_submit',
                            'category'          => $category['name'],
                            'title'             => _MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFY,
                            'caption'           => _MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFYCAP,
                            'description'       => _MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFYDSC,
                            'mail_template_dir' => $mail_template_dir,
                            'mail_template'     => 'commentsubmit_notify',
                            'mail_subject'      => _MD_NOTIFICATIONS_COMMENTSUBMIT_NOTIFYSBJ,
                            'admin_only'        => 1
                        );
                        if (!$enabled_only || $this->eventEnabled($category, $event, $dirname)) {
                            $event_array[] = $event;
                        }
                    }
                }
            }

            // Insert bookmark info if appropriate

            if (!empty($category['allow_bookmark'])) {
                if (!$override_bookmark) {
                    $event = array(
                        'name'        => 'bookmark',
                        'category'    => $category['name'],
                        'title'       => _MD_NOTIFICATIONS_BOOKMARK_NOTIFY,
                        'caption'     => _MD_NOTIFICATIONS_BOOKMARK_NOTIFYCAP,
                        'description' => _MD_NOTIFICATIONS_BOOKMARK_NOTIFYDSC
                    );
                    if (!$enabled_only || $this->eventEnabled($category, $event, $dirname)) {
                        $event_array[] = $event;
                    }
                }
            }

            return $event_array;
        }
        return array();
    }

    /**
     * Determine whether a particular notification event is enabled.
     * Depends on module config options.
     *
     * @todo  Check that this works correctly for comment and other
     *        events which depend on additional config options...
     *
     * @param array  &$category Category info array
     * @param array  &$event    Event info array
     * @param string $dirname   Module
     *
     * @return bool
     **/
    public function eventEnabled(&$category, &$event, $dirname)
    {
        $xoops = Xoops::getInstance();

        $mod_config = $xoops->getModuleConfigs($dirname);

        if (is_array($mod_config['notification_events']) && $mod_config['notification_events'] != array()) {
            $option_name = $this->generateConfig($category, $event, 'option_name');
            if (in_array($option_name, $mod_config['notification_events'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get associative array of info for the selected event in the selected
     * category (for the selected module).
     *
     * @param string $category_name  Notification category
     * @param string $event_name     Notification event
     * @param string $module_dirname Dirname of the module (default current module)
     *
     * @return mixed
     */
    public function getEvent($category_name, $event_name, $module_dirname = null)
    {
        $all_events = $this->getEvents($category_name, false, $module_dirname);
        foreach ($all_events as $event) {
            if ($event['name'] == $event_name) {
                return $event;
            }
        }
        return false;
    }

    /**
     * Get an array of associative info arrays for subscribable categories
     * for the selected module.
     *
     * @param string $module_dirname ID of the module
     *
     * @return mixed
     */
    public function getSubscribableCategories($module_dirname = null)
    {
        $all_categories = $this->getCategory('', $module_dirname);

        // FIXME: better or more standardized way to do this?
        $script_url = explode('/', $_SERVER['PHP_SELF']);
        $script_name = $script_url[count($script_url) - 1];

        $sub_categories = array();
        foreach ($all_categories as $category) {
            // Check the script name
            $subscribe_from = $category['subscribe_from'];
            if (!is_array($subscribe_from)) {
                if ($subscribe_from == '*') {
                    $subscribe_from = array(
                        $script_name
                    );
                    // FIXME: this is just a hack: force a match
                } else {
                    $subscribe_from = array(
                        $subscribe_from
                    );
                }
            }
            if (!in_array($script_name, $subscribe_from)) {
                continue;
            }
            // If 'item_name' is missing, automatic match.  Otherwise
            // check if that argument exists...
            if (empty($category['item_name'])) {
                $category['item_name'] = '';
                $category['item_id'] = 0;
                $sub_categories[] = $category;
            } else {
                $item_name = $category['item_name'];
                $id = ($item_name != '' && isset($_GET[$item_name])) ? intval($_GET[$item_name]) : 0;
                if ($id > 0) {
                    $category['item_id'] = $id;
                    $sub_categories[] = $category;
                }
            }
        }
        return $sub_categories;
    }

    /**
     * Generate module config info for a particular category, event pair.
     * The selectable config options are given names depending on the
     * category and event names, and the text depends on the category
     * and event titles.  These are pieced together in this function in
     * case we wish to alter the syntax.
     *
     * @param array  &$category Array of category info
     * @param array  &$event    Array of event info
     * @param string $type      The particular name to generate
     *
     * @return bool|string
     */
    public function generateConfig(&$category, &$event, $type)
    {
        switch ($type) {
            case 'option_value':
            case 'name':
                return 'notify:' . $category['name'] . '-' . $event['name'];
                break;
            case 'option_name':
                return $category['name'] . '-' . $event['name'];
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * @param XoopsModule $module
     */
    public function insertModuleRelations(XoopsModule $module)
    {
        $xoops = Xoops::getInstance();
        $config_handler = $xoops->getHandlerConfig();
        $configs = $this->getPluginableConfigs($module);

        $order = count($xoops->getModuleConfigs($module->getVar('dirname')));
        foreach ($configs as $config) {
            $confobj = $config_handler->createConfig();
            $confobj->setVar('conf_modid', $module->getVar('mid'));
            $confobj->setVar('conf_catid', 0);
            $confobj->setVar('conf_name', $config['name']);
            $confobj->setVar('conf_title', $config['title'], true);
            $confobj->setVar('conf_desc', $config['description'], true);
            $confobj->setVar('conf_formtype', $config['formtype']);
            $confobj->setVar('conf_valuetype', $config['valuetype']);
            $confobj->setConfValueForInput($config['default'], true);
            $confobj->setVar('conf_order', $order);
            if (isset($config['options']) && is_array($config['options'])) {
                foreach ($config['options'] as $key => $value) {
                    $confop = $config_handler->createConfigOption();
                    $confop->setVar('confop_name', $key, true);
                    $confop->setVar('confop_value', $value, true);
                    $confobj->setConfOptions($confop);
                    unset($confop);
                }
            }
            $order++;
            $config_handler->insertConfig($confobj);
        }
    }

    /**
     * @param XoopsModule $module
     */
    public function deleteModuleRelations(XoopsModule $module)
    {
        $xoops = Xoops::getInstance();
        $this->getHandlerNotification()->unsubscribeByModule($module->getVar('mid'));


        $configNames = array('notifications_enabled', 'notification_events');
        $config_handler = $xoops->getHandlerConfig();

        //Delete all configs
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('conf_modid', $module->getVar('mid')));
        $criteria->add(new Criteria('conf_name', "('" . implode("','", $configNames) . "')", 'IN'));
        $configs = $config_handler->getConfigs($criteria);
        /* @var $config XoopsConfigItem */
        foreach ($configs as $config) {
            $config_handler->deleteConfig($config);
        }
    }

    /**
     * @param XoopsModule $module
     *
     * @return array
     */
    public function getPluginableConfigs(XoopsModule $module)
    {
        $configs = array();
        $options['_MD_NOTIFICATIONS_CONFIG_DISABLE'] = NOTIFICATIONS_DISABLE;
        $options['_MD_NOTIFICATIONS_CONFIG_ENABLEBLOCK'] = NOTIFICATIONS_ENABLEBLOCK;
        $options['_MD_NOTIFICATIONS_CONFIG_ENABLEINLINE'] = NOTIFICATIONS_ENABLEINLINE;
        $options['_MD_NOTIFICATIONS_CONFIG_ENABLEBOTH'] = NOTIFICATIONS_ENABLEBOTH;

        $configs[] = array(
            'name'        => 'notifications_enabled',
            'title'       => '_MD_NOTIFICATIONS_CONFIG_ENABLE',
            'description' => '_MD_NOTIFICATIONS_CONFIG_ENABLEDSC',
            'formtype'    => 'select',
            'valuetype'   => 'int',
            'default'     => NOTIFICATIONS_ENABLEBOTH,
            'options'     => $options
        );
        // Event-specific notification options
        // FIXME: doesn't work when update module... can't read back the array of options properly...  " changing to &quot;
        $options = array();
        $categories = $this->getCategory('', $module->getVar('dirname'));
        foreach ($categories as $category) {
            $events = $this->getEvents($category['name'], false, $module->getVar('dirname'));
            foreach ($events as $event) {
                if (!empty($event['invisible'])) {
                    continue;
                }
                $option_name = $category['title'] . ' : ' . $event['title'];
                $option_value = $category['name'] . '-' . $event['name'];
                $options[$option_name] = $option_value;
            }
            unset($events);
        }
        unset($categories);
        $configs[] = array(
            'name'        => 'notification_events',
            'title'       => '_MD_NOTIFICATIONS_CONFIG_EVENTS',
            'description' => '_MD_NOTIFICATIONS_CONFIG_EVENTSDSC',
            'formtype'    => 'select_multi',
            'valuetype'   => 'array',
            'default'     => array_values($options),
            'options'     => $options
        );
        return $configs;
    }
}

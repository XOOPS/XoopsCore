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
 * page module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

class PageNotificationsPlugin extends Xoops\Module\Plugin\PluginAbstract implements NotificationsPluginInterface
{
    /**
     * @param string $category
     * @param int    $item_id
     *
     * @return array
     */
    public function item($category, $item_id)
    {
        $xoops = Xoops::getInstance();
        $item = array();
        $item_id = (int) $item_id;

        if ($category == 'global') {
            $item['name'] = '';
            $item['url'] = '';
            return $item;
        }

        if ($category == 'item') {
            $sql = 'SELECT content_title FROM ' . $xoopsDB->prefix('page_content') . ' WHERE content_id = ' . $item_id;
            $result = $xoopsDB->query($sql); // TODO: error check
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['title'];
            $item['url'] = \XoopsBaseConfig::get('url') . '/modules/page/viewpage.php?id=' . $item_id;
            return $item;
        }

        return $item;
    }

    /**
     * @return array
     */
    public function categories()
    {
        Xoops::getInstance()->loadLocale('page');

        $ret = array();
        $ret[1]['name'] = 'global';
        $ret[1]['title'] = PageLocale::NOTIFICATION_GLOBAL;
        $ret[1]['description'] = PageLocale::NOTIFICATION_GLOBAL_DSC;
        $ret[1]['subscribe_from'] = array('index.php', 'viewpage.php');

        $ret[2]['name'] = 'item';
        $ret[2]['title'] = PageLocale::NOTIFICATION_ITEM;
        $ret[2]['description'] = PageLocale::NOTIFICATION_ITEM_DSC;
        $ret[2]['subscribe_from'] = array('viewpage.php');
        $ret[2]['item_name'] = 'id';
        $ret[2]['allow_bookmark'] = 1;
        return $ret;
    }

    /**
     * @return array
     */
    public function events()
    {
        Xoops::getInstance()->loadLocale('page');

        $ret = array();
        $ret[1]['name'] = 'newcontent';
        $ret[1]['category'] = 'global';
        $ret[1]['title'] = PageLocale::NOTIFICATION_GLOBAL_NEWCONTENT;
        $ret[1]['caption'] = PageLocale::NOTIFICATION_GLOBAL_NEWCONTENT_CAP;
        $ret[1]['description'] = PageLocale::NOTIFICATION_GLOBAL_NEWCONTENT_DSC;
        $ret[1]['mail_template'] = 'global_newcontent';
        $ret[1]['mail_subject'] = PageLocale::NOTIFICATION_GLOBAL_NEWCONTENT_SBJ;
        return $ret;
    }
    /**
     * @param string $category
     * @param int    $item_id
     * @param string $event
     *
     * @return array
     */
    public function tags($category, $item_id, $event)
    {
        return array();
    }
}

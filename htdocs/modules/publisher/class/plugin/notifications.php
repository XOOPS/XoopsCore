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
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Class
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once dirname(dirname(__DIR__)) . '/include/common.php';

class PublisherNotificationsPlugin extends Xoops\Module\Plugin\PluginAbstract implements NotificationsPluginInterface
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

        if ($category == 'category') {
            // Assume we have a valid category id
            $sql = 'SELECT name, short_url FROM ' . $xoopsDB->prefix('publisher_categories') . ' WHERE categoryid  = ' . $item_id;
            $result = $xoopsDB->query($sql); // TODO: error check
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['name'];
            $item['url'] = PublisherUtils::seoGenUrl('category', $item_id, $result_array['short_url']);
            return $item;
        }

        if ($category == 'item') {
            // Assume we have a valid story id
            $sql = 'SELECT title, short_url FROM ' . $xoopsDB->prefix('publisher_items') . ' WHERE itemid = ' . $item_id;
            $result = $xoopsDB->query($sql); // TODO: error check
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['title'];
            $item['url'] = PublisherUtils::seoGenUrl('item', $item_id, $result_array['short_url']);
            return $item;
        }

        return $item;
    }

    /**
     * @return array
     */
    public function categories()
    {
        $ret = array();
        $ret[1]['name'] = 'global';
        $ret[1]['title'] = _MI_PUBLISHER_GLOBAL_ITEM_NOTIFY;
        $ret[1]['description'] = _MI_PUBLISHER_GLOBAL_ITEM_NOTIFY_DSC;
        $ret[1]['subscribe_from'] = array('index.php', 'category.php', 'item.php');

        $ret[2]['name'] = 'category';
        $ret[2]['title'] = _MI_PUBLISHER_CATEGORY_ITEM_NOTIFY;
        $ret[2]['description'] = _MI_PUBLISHER_CATEGORY_ITEM_NOTIFY_DSC;
        $ret[2]['subscribe_from'] = array('index.php', 'category.php', 'item.php');
        $ret[2]['item_name'] = 'categoryid';
        $ret[2]['allow_bookmark'] = 1;

        $ret[3]['name'] = 'item';
        $ret[3]['title'] = _MI_PUBLISHER_ITEM_NOTIFY;
        $ret[3]['description'] = _MI_PUBLISHER_ITEM_NOTIFY_DSC;
        $ret[3]['subscribe_from'] = array('item.php');
        $ret[3]['item_name'] = 'itemid';
        $ret[3]['allow_bookmark'] = 1;
        return $ret;
    }

    /**
     * @return array
     */
    public function events()
    {
        $ret = array();
        $ret[1]['name'] = 'category_created';
        $ret[1]['category'] = 'global';
        $ret[1]['title'] = _MI_PUBLISHER_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY;
        $ret[1]['caption'] = _MI_PUBLISHER_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY_CAP;
        $ret[1]['description'] = _MI_PUBLISHER_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY_DSC;
        $ret[1]['mail_template'] = 'global_item_category_created';
        $ret[1]['mail_subject'] = _MI_PUBLISHER_GLOBAL_ITEM_CATEGORY_CREATED_NOTIFY_SBJ;

        $ret[2]['name'] = 'submitted';
        $ret[2]['category'] = 'global';
        $ret[2]['admin_only'] = 1;
        $ret[2]['title'] = _MI_PUBLISHER_GLOBAL_ITEM_SUBMITTED_NOTIFY;
        $ret[2]['caption'] = _MI_PUBLISHER_GLOBAL_ITEM_SUBMITTED_NOTIFY_CAP;
        $ret[2]['description'] = _MI_PUBLISHER_GLOBAL_ITEM_SUBMITTED_NOTIFY_DSC;
        $ret[2]['mail_template'] = 'global_item_submitted';
        $ret[2]['mail_subject'] = _MI_PUBLISHER_GLOBAL_ITEM_SUBMITTED_NOTIFY_SBJ;

        $ret[3]['name'] = 'published';
        $ret[3]['category'] = 'global';
        $ret[3]['title'] = _MI_PUBLISHER_GLOBAL_ITEM_PUBLISHED_NOTIFY;
        $ret[3]['caption'] = _MI_PUBLISHER_GLOBAL_ITEM_PUBLISHED_NOTIFY_CAP;
        $ret[3]['description'] = _MI_PUBLISHER_GLOBAL_ITEM_PUBLISHED_NOTIFY_DSC;
        $ret[3]['mail_template'] = 'global_item_published';
        $ret[3]['mail_subject'] = _MI_PUBLISHER_GLOBAL_ITEM_PUBLISHED_NOTIFY_SBJ;

        $ret[4]['name'] = 'submitted';
        $ret[4]['category'] = 'category';
        $ret[4]['admin_only'] = 1;
        $ret[4]['title'] = _MI_PUBLISHER_CATEGORY_ITEM_SUBMITTED_NOTIFY;
        $ret[4]['caption'] = _MI_PUBLISHER_CATEGORY_ITEM_SUBMITTED_NOTIFY_CAP;
        $ret[4]['description'] = _MI_PUBLISHER_CATEGORY_ITEM_SUBMITTED_NOTIFY_DSC;
        $ret[4]['mail_template'] = 'category_item_submitted';
        $ret[4]['mail_subject'] = _MI_PUBLISHER_CATEGORY_ITEM_SUBMITTED_NOTIFY_SBJ;

        $ret[5]['name'] = 'published';
        $ret[5]['category'] = 'category';
        $ret[5]['title'] = _MI_PUBLISHER_CATEGORY_ITEM_PUBLISHED_NOTIFY;
        $ret[5]['caption'] = _MI_PUBLISHER_CATEGORY_ITEM_PUBLISHED_NOTIFY_CAP;
        $ret[5]['description'] = _MI_PUBLISHER_CATEGORY_ITEM_PUBLISHED_NOTIFY_DSC;
        $ret[5]['mail_template'] = 'category_item_published';
        $ret[5]['mail_subject'] = _MI_PUBLISHER_CATEGORY_ITEM_PUBLISHED_NOTIFY_SBJ;

        $ret[6]['name'] = 'rejected';
        $ret[6]['category'] = 'item';
        $ret[6]['invisible'] = 1;
        $ret[6]['title'] = _MI_PUBLISHER_ITEM_REJECTED_NOTIFY;
        $ret[6]['caption'] = _MI_PUBLISHER_ITEM_REJECTED_NOTIFY_CAP;
        $ret[6]['description'] = _MI_PUBLISHER_ITEM_REJECTED_NOTIFY_DSC;
        $ret[6]['mail_template'] = 'item_rejected';
        $ret[6]['mail_subject'] = _MI_PUBLISHER_ITEM_REJECTED_NOTIFY_SBJ;

        $ret[7]['name'] = 'approved';
        $ret[7]['category'] = 'item';
        $ret[7]['invisible'] = 1;
        $ret[7]['title'] = _MI_PUBLISHER_ITEM_APPROVED_NOTIFY;
        $ret[7]['caption'] = _MI_PUBLISHER_ITEM_APPROVED_NOTIFY_CAP;
        $ret[7]['description'] = _MI_PUBLISHER_ITEM_APPROVED_NOTIFY_DSC;
        $ret[7]['mail_template'] = 'item_approved';
        $ret[7]['mail_subject'] = _MI_PUBLISHER_ITEM_APPROVED_NOTIFY_SBJ;
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

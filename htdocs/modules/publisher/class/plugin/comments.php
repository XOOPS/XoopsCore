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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Class
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class PublisherCommentsPlugin extends Xoops\Module\Plugin\PluginAbstract implements CommentsPluginInterface
{
    /**
     * @return string
     */
    public function itemName()
    {
        return 'itemid';
    }

    /**
     * @return string
     */
    public function pageName()
    {
        return 'item.php';
    }

    /**
     * @return array
     */
    public function extraParams()
    {
        return array();
    }

    /**
     * This method will be executed upon successful post of an approved comment.
     * This includes comment posts by administrators, and change of comment status from 'pending' to 'active' state.
     * An CommentsComment object that has been approved will be passed as the first and only parameter.
     * This should be useful for example notifying the item submitter of a comment post.
     *
     * @param CommentsComment $comment
     *
     * @return void
     */
    public function approve(CommentsComment $comment)
    {
        //Where are you looking at?
    }

    /**
     * This method will be executed whenever the total number of 'active' comments for an item is changed.
     *
     * @param int $item_id   The unique ID of an item
     * @param int $total_num The total number of active comments
     *
     * @return void
     */
    public function update($item_id, $total_num)
    {
        $db = Xoops::getInstance()->db();
        $sql = 'UPDATE ' . $db->prefix('publisher_items') . ' SET comments = ' . intval($total_num) . ' WHERE itemid = ' . intval($item_id);
        $db->query($sql);
    }

    /**
     * This method will be executed whenever a new comment form is displayed.
     * You can set a default title for the comment and a header to be displayed on top of the form
     * ex: return array(
     *      'title' => 'My Article Title',
     *      'text' => 'Content of the article');
     *      'timestamp' => time(); //Date of the article in unix format
     *      'uid' => Id of the article author
     *
     * @param int $item_id The unique ID of an item
     *
     * @return array
     */
    public function itemInfo($item_id)
    {
        $ret = array();
        include_once dirname(dirname(__DIR__)) . '/include/common.php';

        /* @var $itemObj PublisherItem */
        $itemObj = Publisher::getInstance()->getItemHandler()->get(intval($item_id));
        $ret['text'] = '';
        $summary = $itemObj->summary();
        if ($summary != '') {
            $ret['text'] .= $summary . '<br /><br />';
        }
        $ret['text'] .= $itemObj->body();
        $ret['title'] = $itemObj->title();
        $ret['uid'] = $itemObj->getVar('uid');
        $ret['timestamp'] = $itemObj->getVar('datesub', 'n');
        return $ret;
    }
}

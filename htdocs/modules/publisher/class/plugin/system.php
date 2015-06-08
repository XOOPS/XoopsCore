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
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

class PublisherSystemPlugin extends Xoops\Module\Plugin\PluginAbstract implements SystemPluginInterface
{
    /**
     * @param int $uid
     *
     * @return int
     */
    public function userPosts($uid)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 2));
        $criteria->add(new Criteria('uid', (int)$uid));
        return Publisher::getInstance()->getItemHandler()->getCount($criteria);
    }

    /**
     * @return array
     */
    public function waiting()
    {
        $publisher = Publisher::getInstance();
        $ret = array();
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 1));
        $count = $publisher->getItemHandler()->getCount($criteria);
        if ($count) {
            $ret['count'] = $count;
            $ret['name'] = _MI_PUBLISHER_WAITING;
            $ret['link'] = $publisher->url('admin/item.php');
        }
        return $ret;
    }

    /**
     * Used to populate backend
     *
     * @param int $limit : Number of item for backend
     *                   Expects an array containing:
     *                   title   : Title for the backend items
     *                   link    : Link for the backend items
     *                   content : content for the backend items
     *                   date    : Date of the backend items
     *
     * @return array
     */
    public function backend($limit)
    {
        // TODO: Implement backend() method.
    }

    /**
     * Used to populate the User Block
     * Expects an array containing:
     *    name  : Name for the Link
     *    link  : Link relative to module
     *    image : Url of image to display, please use 16px*16px image
     *
     * @return array
     */
    public function userMenus()
    {
        // TODO: Implement userMenus() method.
    }
}

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
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

class PageSystemPlugin extends Xoops\Module\Plugin\PluginAbstract implements SystemPluginInterface
{
    /**
     * @param int $uid
     *
     * @return int
     */
    public function userPosts($uid)
    {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('content_status', 0, '!='));
        $criteria->add(new Criteria('content_author', (int)$uid));
        return Page::getInstance()->getContentHandler()->getCount($criteria);
    }

    /**
     * @return array
     */
    public function waiting()
    {
        $criteria = new CriteriaCompo(new Criteria('content_status', 0));
        if ($count = Page::getInstance()->getContentHandler()->getCount($criteria)) {
            $ret['count'] = $count;
            $ret['name'] = Page::getInstance()->getModule()->getVar('name');
            $ret['link'] = Page::getInstance()->url('admin/content.php');
            return $ret;
        }
        return array();
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
        $ret = array();
        $contents = Page::getInstance()->getContentHandler()->getPagePublished(0, $limit);
        foreach ($contents as $k => $content) {
            $ret[$k]['title']   = $content->getVar('content_title');
            $ret[$k]['link']    = Page::getInstance()->url('viewpage.php') . '?id=' . $content->getVar('content_id');
            $ret[$k]['content'] = $content->getVar('content_shorttext') . '<br />' . $content->getVar('content_text');
            $ret[$k]['date']    = $content->getVar('content_create');
        }
        return $ret;
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

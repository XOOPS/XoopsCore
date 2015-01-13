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
 * Codex module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Codex
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

class CodexSystemPlugin extends Xoops\Module\Plugin\PluginAbstract implements SystemPluginInterface
{
    /**
     * Used to synchronize a user number of posts
     * Please return the number of posts the user as made in your module
     *
     * @param int $uid The uid of the user
     *
     * @return int Number of posts
     */
    public function userPosts($uid)
    {
        //$xoops = Xoops::getInstance();
        //$count = count(XoopsLists::getFileListAsArray($xoops->path('modules/codex/')))-2;
        return 0;
    }

    /**
     * Used to populate the Waiting Block
     *
     * Expects an array containing:
     *    count : Number of waiting items,    ex: 3
     *    name  : Name for the waiting items, ex: Pending approval
     *    link  : Link for the waiting items, ex: Xoops::getInstance()->url('modules/comments/admin/main.php');
     *
     * @return array
     */
    public function waiting()
    {
        $xoops = Xoops::getInstance();
        $ret['count'] = count(XoopsLists::getFileListAsArray($xoops->path('modules/codex/')))-2;
        $ret['name'] = $xoops->getHandlerModule()->getBydirname('codex')->getVar('name');
        $ret['link'] = $xoops->url('modules/codex/');
        return array();
    }

    /**
     * Used to populate backend
     *
     * @param int $limit : Number of item for backend
     *
     * Expects an array containing:
     *    title   : Title for the backend items
     *    link    : Link for the backend items
     *    content : content for the backend items
     *    date    : Date of the backend items
     *
     * @return array
     */
    public function backend($limit)
    {
        $xoops = Xoops::getInstance();
        $i=0;
        $ret=array();

        $files = XoopsLists::getFileListAsArray($xoops->path('modules/codex/'));
        foreach ($files as $file) {
            if (!in_array($file, array('xoops_version.php', 'index.php'))) {
                $ret[$i]['title']   = ucfirst(str_replace('.php', '', $file));
                $ret[$i]['link']    = $xoops->url('modules/codex/' . $file);
                $ret[$i]['content'] = 'Codex module : ' . ucfirst(str_replace('.php', '', $file));
                $ret[$i]['date']    = filemtime($xoops->path('modules/codex/' . $file));
                $i++;
            }
        }
        return $ret;
    }

    /**
     * Used to populate the User Block
     *
     * Expects an array containing:
     *    name  : Name for the Link
     *    link  : Link relative to module
     *    image : Url of image to display, please use 16px*16px image
     *
     * @return array
     */
    public function userMenus()
    {
        /*$xoops = Xoops::getInstance();
        $ret['name'] = Xoops::getInstance()->getHandlerModule()->getBydirname('codex')->getVar('name');
        $ret['link'] = 'index.php';
        $ret['image'] = $xoops->url('modules/codex/icons/logo_small.png');
        return $ret;*/
    }
}

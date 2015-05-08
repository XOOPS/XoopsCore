<?php
/**
 * Comments module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Comments
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

class CommentsSystemPlugin extends Xoops\Module\Plugin\PluginAbstract implements SystemPluginInterface
{
    /**
     * @param int $uid
     *
     * @return int
     */
    public function userPosts($uid)
    {
        $comments = Comments::getInstance(); //need this here to init constants
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', COMMENTS_ACTIVE));
        $criteria->add(new Criteria('uid', $uid));
        return Comments::getInstance()->getHandlerComment()->getCount($criteria);
    }

    /**
     * @return array
     */
    public function waiting()
    {
        $comments = Comments::getInstance(); //need this here to init constants
        $criteria = new CriteriaCompo(new Criteria('status', COMMENTS_PENDING));
        $ret = array();
        if ($count = $comments->getHandlerComment()->getCount($criteria)) {
            $ret['count'] = $count;
            $ret['name'] = Xoops::getInstance()->getHandlerModule()->getBydirname('comments')->getVar('name');
            $ret['link'] = Xoops::getInstance()->url('modules/comments/admin/main.php');
        }
        return $ret;
    }

    public function backend($limit)
    {
        return array();
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

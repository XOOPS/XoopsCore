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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 *
 * @package         Comments
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class CommentsWaitingPlugin implements WaitingPluginInterface
{

    /**
     * @return array
     */
    public function waiting()
    {
        /* @var $comments Comments */
        $comments = \Xoops::getModuleHelper('comments'); //need this here to init constants
        $criteria = new CriteriaCompo(new Criteria('status', Comments::STATUS_PENDING));
        $ret = array();
        if ($count = $comments->getHandlerComment()->getCount($criteria)) {
            $ret[] = [
                'count' => $count,
                'name' => $comments->getModule()->getVar('name'),
                'link' => $comments->url('admin/main.php')
            ];
        }
        return $ret;
    }
}

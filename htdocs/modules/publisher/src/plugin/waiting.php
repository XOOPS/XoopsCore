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
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Laurent JEN (aka DuGris)
 */
class PublisherWaitingPlugin implements WaitingPluginInterface
{

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
            $ret[] = [
                'count' => $count,
                'name' => _MI_PUBLISHER_WAITING,
                'link' => $publisher->url('admin/item.php')
            ];
        }
        return $ret;
    }
}

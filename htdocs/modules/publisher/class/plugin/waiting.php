<?php

namespace XoopsModules\Publisher\Plugin;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Criteria;
use CriteriaCompo;
use WaitingPluginInterface;
use XoopsModules\Publisher\Helper;

/**
 * @copyright        2000-2020 XOOPS Project (https://xoops.org)
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
        $helper = Helper::getInstance();
        $ret = [];
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('status', 1));
        $count = $helper->getItemHandler()->getCount($criteria);
        if ($count) {
            $ret[] = [
                'count' => $count,
                'name' => _MI_PUBLISHER_WAITING,
                'link' => $helper->url('admin/item.php'),
            ];
        }

        return $ret;
    }
}

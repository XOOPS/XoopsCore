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
 */
class PageWaitingPlugin implements WaitingPluginInterface
{
    /**
     * @return array
     */
    public function waiting()
    {
        $ret = [];

        /* @var $page Page */
        $page = \Xoops::getModuleHelper('page');

        $criteria = new CriteriaCompo(new Criteria('content_status', 0));

        if ($count = $page->getContentHandler()->getCount($criteria)) {
            $ret[] = [
                'count' => $count,
                'name' => $page->getModule()->getVar('name'),
                'link' => $page->url('admin/content.php'),
            ];
        }

        return $ret;
    }
}

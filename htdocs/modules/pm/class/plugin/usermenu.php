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
class PmUsermenuPlugin implements UsermenuPluginInterface
{
    /**
     * @return array
     */
    public function usermenu()
    {
        $xoops = \Xoops::getInstance();
        $helper = \Xoops::getModuleHelper('pm');
        $ret = [];

        $criteria = new CriteriaCompo(new Criteria('read_msg', 0));
        $criteria->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
        $pm_handler = $helper->getHandler('message');

        $name = XoopsLocale::INBOX;
        if ($pm_count = $pm_handler->getCount($criteria)) {
            $name = XoopsLocale::INBOX . ' <span class="badge">' . $pm_count . '</span>';
        }

        $ret[] = [
            'name' => $name,
            'link' => $helper->url('viewpmsg.php'),
        ];

        return $ret;
    }
}

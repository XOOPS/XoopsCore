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
class SystemUsermenuPlugin implements UsermenuPluginInterface
{

    /**
     * @return array
     */
    public function usermenu()
    {
        $xoops = \Xoops::getInstance();
        $ret = array();
        // View Account
        $ret[] = [
            'name' => XoopsLocale::VIEW_ACCOUNT,
            'link' => $xoops->url('userinfo.php?uid=' . $xoops->user->getVar('uid')),
            'icon' => 'glyphicon-user',
        ];

        // Edit Account
        $ret[] = [
            'name' => XoopsLocale::EDIT_ACCOUNT,
            'link' => $xoops->url('edituser.php'),
            'icon' => 'glyphicon-pencil',
        ];

        // Administration Menu
        if ($xoops->isAdmin()) {
            $ret[] = [
                'name' => SystemLocale::ADMINISTRATION_MENU,
                'link' => $xoops->url('admin.php'),
                'icon' => 'glyphicon-wrench',
            ];
        }

        // Inbox
        if (!$xoops->isActiveModule('pm')) {
            $criteria = new CriteriaCompo(new Criteria('read_msg', 0));
            $criteria->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
            $pm_handler = $xoops->getHandlerPrivateMessage();
            $xoops->events()->triggerEvent('system.blocks.system_blocks.usershow', array(&$pm_handler));

            $name = XoopsLocale::INBOX;
            if ($pm_count = $pm_handler->getCount($criteria)) {
                $name = XoopsLocale::INBOX . ' <span class="badge">' . $pm_count . '</span>';
            }

            $ret[] = [
                'name' => $name,
                'link' => $xoops->url('viewpmsg.php'),
                'icon' => 'glyphicon-envelope',
            ];
        }

        // Logout
        $ret[] = [
            'name' => XoopsLocale::A_LOGOUT,
            'link' => $xoops->url('user.php?op=logout'),
            'icon' => 'glyphicon-log-out',
        ];

        return $ret;
    }
}
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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class NotificationsSystemPlugin extends Xoops\Module\Plugin\PluginAbstract implements SystemPluginInterface
{
    public function userPosts($uid)
    {
        return [];
    }

    public function waiting()
    {
        return 0;
    }

    public function backend($limit)
    {
        return [];
    }

    public function userMenus()
    {
        $helper = Notifications::getInstance();
        $ret['name'] = $helper->getModule()->getVar('name');
        $ret['link'] = 'index.php';
        $ret['image'] = $helper->url('icons/logo_small.png');

        return $ret;
    }
}

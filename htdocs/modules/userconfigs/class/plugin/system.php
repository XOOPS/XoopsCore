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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class UserconfigsSystemPlugin extends Xoops_Module_Plugin_Abstract implements SystemPluginInterface
{
    public function userPosts($uid) {return array();}
    public function waiting() {return 0;}
    public function backend($limit) {return array();}

    public function userMenus()
    {
        $helper = Userconfigs::getInstance();
        $ret['name'] = $helper->getModule()->getVar('name');
        $ret['link'] = 'index.php';
        $ret['image'] = $helper->url('icons/logo_small.png');
        return $ret;
    }
}

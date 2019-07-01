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

use MainmenuPluginInterface;
use Xoops;

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class PublisherMainmenuPlugin implements MainmenuPluginInterface
{
    /**
     * @return array
     */
    public function mainmenu()
    {
        $helper = Xoops::getModuleHelper(\basename(\dirname(\dirname(__DIR__))));
        $subMenu = [];
        // Prevent wasting resources
        if ($helper->isCurrentModule()) {
            //Todo: Implement submenu;
        }

        $ret[] = [
            'name' => $helper->getModule()->getVar('name'),
            'link' => $helper->url(),
            'subMenu' => $subMenu,
        ];

        return $ret;
    }
}

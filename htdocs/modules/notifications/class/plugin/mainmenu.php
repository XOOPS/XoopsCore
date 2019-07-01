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
class NotificationsMainmenuPlugin implements MainmenuPluginInterface
{
    /**
     * @return array
     */
    public function mainmenu()
    {
        $helper = \Xoops::getModuleHelper(basename(dirname(dirname(__DIR__))));
        $ret[] = [
            'name' => $helper->getModule()->getVar('name'),
            'link' => $helper->url(),
        ];

        return $ret;
    }
}

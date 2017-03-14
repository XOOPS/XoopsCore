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
class ProfileUsermenuPlugin implements UsermenuPluginInterface
{
    /**
     * @return array
     */
    public function usermenu()
    {
        $helper = \Xoops::getModuleHelper(basename(dirname(dirname(__DIR__))));
        $helper->loadLanguage('modinfo');
        // View Account
        $ret[] = [
            'name' => XoopsLocale::VIEW_ACCOUNT,
            'link' => $helper->url('userinfo.php?uid=' . $helper->xoops()->user->getVar('uid')),
            'icon' => 'glyphicon-user',
        ];

        // Edit Account
        $ret[] = [
            'name' => _PROFILE_MI_EDITACCOUNT,
            'link' => $helper->url('edituser.php'),
            'icon' => 'glyphicon-pencil',
        ];

        return $ret;
    }
}

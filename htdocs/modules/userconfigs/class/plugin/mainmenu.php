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
class UserconfigsMainmenuPlugin implements MainmenuPluginInterface
{
    /**
     * @return array
     */
    public function mainmenu()
    {
        $helper = \Xoops::getModuleHelper(basename(dirname(dirname(__DIR__))));
        $subMenu = array();
        // Prevent wasting resources
        if ($helper->isCurrentModule()) {
            $xoops = \Xoops::getInstance();
            if ($plugins = \Xoops\Module\Plugin::getPlugins('userconfigs')) {
                foreach (array_keys($plugins) as $dirname) {
                    $mHelper = $xoops->getModuleHelper($dirname);
                    $subMenu[] = [
                        'name' => $mHelper->getModule()->getVar('name'),
                        'link' => $helper->url('index.php?op=showmod&mid=' . $mHelper->getModule()->getVar('mid'))
                    ];
                }
            }
        }

        $ret[] = [
            'name' => $helper->getModule()->getVar('name'),
            'link' => $helper->url(),
            'subMenu' => $subMenu,
        ];
        return $ret;
    }
}

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

class UserconfigsMenusPlugin extends Xoops\Module\Plugin\PluginAbstract implements MenusPluginInterface
{
    /**
     * expects an array of array containing:
     * name,      Name of the submenu
     * url,       Url of the submenu relative to the module
     * ex: return array(0 => array(
     *      'name' => _MI_PUBLISHER_SUB_SMNAME3;
     *      'url' => "search.php";
     *    ));
     *
     * @return array
     */
    public function subMenus()
    {
        $ret = array();
        $xoops = \Xoops::getInstance();
        if ($plugins = \Xoops\Module\Plugin::getPlugins('userconfigs')) {
            foreach (array_keys($plugins) as $dirname) {
                $mHelper = $xoops->getModuleHelper($dirname);
                $ret[$dirname]['name'] = $mHelper->getModule()->getVar('name');
                $ret[$dirname]['url'] = 'index.php?op=showmod&mid=' . $mHelper->getModule()->getVar('mid');
            }
        }

        return $ret;
    }
}

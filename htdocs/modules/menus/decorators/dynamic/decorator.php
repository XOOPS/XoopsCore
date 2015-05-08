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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class MenusDynamicDecorator extends MenusDecoratorAbstract implements MenusDecoratorInterface
{
    public function accessFilter(&$accessFilter)
    {
    }

    public function decorateMenu(&$menu)
    {
    }

    public function end(&$menus)
    {
        $ret = array();
        foreach ($menus as $menu) {
            if (!preg_match('/{(MODULE\|.*)}/i', $menu['title'], $reg)) {
                $ret[] = $menu;
                continue;
            }
            $result = array_map('strtolower', explode('|', $reg[1]));
            $moduleMenus = self::getModuleMenus($result[1], $menu['pid']);
            foreach ($moduleMenus as $mMenu) {
                $ret[] = $mMenu;
            }
        }
        $menus = $ret;
    }

    public function hasAccess($menu, &$hasAccess)
    {
    }

    public function start()
    {
    }

    protected function getModuleMenus($dirname, $pid)
    {
        static $id = -1;
        $xoops = Xoops::getInstance();
        $helper = Menus::getInstance();
        $ret = array();

        /* @var $plugin MenusPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'menus')) {
            if (is_array($subMenus = $plugin->subMenus())) {
                foreach ($subMenus as $menu) {
                    $obj = $helper->getHandlerMenu()->create();
                    $obj->setVar('title', $menu['name']);
                    $obj->setVar('alt_title', $menu['name']);
                    $obj->setVar('link', $xoops->url("modules/{$dirname}/{$menu['url']}"));
                    $obj->setVar('id', $id);
                    $obj->setVar('pid', $pid);
                    $ret[] = $obj->getValues();
                    $id--;
                }
            }
        }
        return $ret;
    }
}

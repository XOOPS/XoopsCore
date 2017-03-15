<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Module\Plugin;

/**
 * mainmenu module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         mainmenu
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
function b_mainmenu_mainmenu_show()
{
    $block = array();
    $block['mainmenu'] = array();

    //Check read permissions
    $readAllowed = [];
    $xoops = Xoops::getInstance();
    $modulePermHandler = $xoops->getHandlerGroupPermission();
    $groups = $xoops->getUserGroups();
    $readAllowedIds = $modulePermHandler->getItemIds('module_read', $groups);
    foreach ($readAllowedIds as $id) {
        $readAllowed[] = $xoops->getModuleById($id)->getVar('dirname');
    }
    $plugins = Plugin::getPlugins('mainmenu');

    /* @var $plugin MainmenuPluginInterface */
    foreach ($plugins as $dirName => $plugin) {
        if (in_array($dirName, $readAllowed) && is_array($results = $plugin->mainmenu())) {
            $helper = \Xoops::getModuleHelper($dirName);
            foreach ($results as $res) {
                if (is_array($res) && isset($res['name']) && isset($res['link'])) {
                    $res['image'] = false;
                    if (!isset($res['icon']) && XoopsLoad::fileExists($helper->path('icons/logo_small.png'))) {
                        $res['image'] = $helper->url('icons/logo_small.png');
                        $res['icon'] = "$dirName-icon";
                    } elseif (!isset($res['icon'])) {
                        $res['icon'] = 'glyphicon-time';
                    }

                    // Handle submenu
                    if ($helper->isCurrentModule() && isset($res['subMenu']) && is_array($res['subMenu'])) {
                        foreach ($res['subMenu'] as  $key => $subMenu) {
                            if (isset($subMenu['name']) && isset($subMenu['link'])) {
                                $subMenu['icon'] = isset($subMenu['icon']) ? $subMenu['icon'] :'glyphicon-menu-right';
                                $res['subMenu'][$key] = $subMenu;
                            }
                        }
                    } else {
                        $res['subMenu'] = false;
                    }

                    //Handle active
                    $res['isActive'] = $helper->isCurrentModule();

                    $res['dirName'] = $dirName;
                    $block['mainmenu'][] = $res;
                }
            }
        }
    }

    $block['count'] = count($block['mainmenu']);
    return $block['count'] ? $block : false;
}

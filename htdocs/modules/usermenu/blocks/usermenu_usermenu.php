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
 * usermenu module
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         usermenu
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
function b_usermenu_usermenu_show()
{
    // Check permissions
    if (!\Xoops::getInstance()->isUser()) {
        return false;
    }

    $block = [];
    $block['usermenu'] = [];
    $plugins = Plugin::getPlugins('usermenu');
    /* @var $plugin UsermenuPluginInterface */
    foreach ($plugins as $dirName => $plugin) {
        $helper = \Xoops::getModuleHelper($dirName);

        if (is_array($results = $plugin->usermenu())) {
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
                                $subMenu['icon'] = isset($subMenu['icon']) ? $subMenu['icon'] : 'glyphicon-menu-right';
                                $res['subMenu'][$key] = $subMenu;
                            }
                        }
                    } else {
                        $res['subMenu'] = false;
                    }

                    //Handle active
                    $activeUrl = \Xoops\Core\HttpRequest::getInstance()->getUrl();
                    $res['isActive'] = 0 === mb_strpos($activeUrl, $res['link']);

                    $res['dirName'] = $dirName;
                    $block['usermenu'][] = $res;
                }
            }
        }
    }
    $block['count'] = count($block['usermenu']);

    return $block['count'] ? $block : false;
}

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
 * waiting module
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         waiting
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
function b_waiting_waiting_show()
{
    $block = [];
    $block['waiting'] = [];
    $plugins = Plugin::getPlugins('waiting');
    /* @var $plugin WaitingPluginInterface */
    foreach ($plugins as $dirName => $plugin) {
        //No permissions, no links
        $helper = \Xoops::getModuleHelper($dirName);
        if (!$helper->isUserAdmin()) {
            continue;
        }

        if (is_array($results = $plugin->waiting())) {
            foreach ($results as $res) {
                if (is_array($res) && isset($res['count']) && isset($res['name']) && isset($res['link'])) {
                    $ret['image'] = false;
                    //Image support
                    if (XoopsLoad::fileExists($helper->path('icons/logo_small.png'))) {
                        $res['image'] = $helper->url('icons/logo_small.png');
                        $res['icon'] = "$dirName-icon";
                    } else {
                        //Icon support
                        $res['icon'] = isset($res['icon']) ? $res['icon'] : 'glyphicon-time';
                    }

                    $res['dirName'] = $dirName;
                    $block['waiting'][] = $res;
                }
            }
        }
    }
    $block['count'] = count($block['waiting']);

    return $block['count'] ? $block : false;
}

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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         waiting
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
function b_waiting_waiting_show()
{
    $block = array();
    $block['waiting'] = array();
    $plugins = Plugin::getPlugins('waiting');
    /* @var $plugin WaitingPluginInterface */
    foreach ($plugins as $plugin) {
        if (is_array($results = $plugin->waiting())) {
            foreach ($results as $res) {
                if (is_array($res) && isset($res['count']) && isset($res['name']) && isset($res['link'])) {
                    $res['icon'] = isset($res['icon']) ? $res['icon'] : 'glyphicon-time';
                    $block['waiting'][] = $res;
                }
            }
        }
    }
    $block['count'] = count($block['waiting']);
    return $block['count'] ? $block : false;
}

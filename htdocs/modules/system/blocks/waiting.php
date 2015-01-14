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
 * Blocks functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

function b_system_waiting_show()
{
    $block = array();
    $plugins = \Xoops\Module\Plugin::getPlugins('system');
    /* @var $plugin SystemPluginInterface */
    foreach ($plugins as $plugin) {
        if (is_array($res = $plugin->waiting())) {
            if (isset($res['count']) && isset($res['name']) && isset($res['link'])) {
                $block['waiting'][] = $res;
            }
        }
    }
    return $block;
}

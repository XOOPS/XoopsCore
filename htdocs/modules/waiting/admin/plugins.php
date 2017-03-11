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
 * plugins module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         plugins
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
include __DIR__ . '/header.php';

// Get main instance
$xoops = \Xoops::getInstance();


// Call header
$xoops->header('admin:waiting/waiting_admin_plugins.tpl');
(new \Xoops\Module\Admin())->renderNavigation('plugins.php');

$contents = array();
$plugins = Plugin::getPlugins('waiting');
foreach ($plugins as $dirName => $plugin) {

    //No permissions, no links
    $helper = \Xoops::getModuleHelper($dirName);
    if (!$helper->isUserAdmin()) {
        continue;
    }

    /* @var $plugin WaitingPluginInterface */
    if (is_array($results = $plugin->waiting())) {
        foreach ($results as $res) {
            if (is_array($res) && isset($res['count']) && isset($res['name']) && isset($res['link'])) {
                $contents[] = [
                    'pluginDirName' => $dirName,
                    'pluginName' => $xoops->getModuleByDirname($dirName)->getVar('name'),
                    'pluginItems' => $res
                ];
            } else {
                $contents[] = [
                    'pluginDirName' => $dirName,
                    'pluginName' => $xoops->getModuleByDirname($dirName)->getVar('name'),
                    'pluginItems' => [
                        'name' => '',
                        'link' => '',
                        'count' => 0
                    ]
                ];
            }
        }
    }
}

$xoops->tpl()->assign('contents', $contents);
$xoops->tpl()->assign('count', count($contents));

$xoops->footer();

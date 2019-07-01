<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * Blocks functions
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */
function b_system_user_show()
{
    $xoops = Xoops::getInstance();
    if (!$xoops->isUser()) {
        return false;
    }

    $block = [];
    $block['modules'] = [];

    $plugins = \Xoops\Module\Plugin::getPlugins('system');
    $i = 0;
    /* @var $plugin SystemPluginInterface */
    foreach ($plugins as $dirname => $plugin) {
        $menu = $plugin->userMenus();

        if (is_array($menu) && !empty($menu)) {
            $block['modules'][$i]['name'] = $menu['name'];
            $block['modules'][$i]['link'] = $xoops->url('modules/' . $dirname . '/' . $menu['link']);
            $block['modules'][$i]['image'] = $menu['image'];
            $block['modules'][$i]['icon'] = 'glyphicon glyphicon-none';
            $block['modules'][$i]['dirname'] = $dirname;
            $block['modules'][$i]['title'] = $menu['name'];

            //todo, remove this hardcoded call
            if ($xoops->isModule() && $xoops->module->getVar('dirname') == $dirname && $plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'menus')) {
                if (method_exists($plugin, 'subMenus')) {
                    $sublinks = $plugin->subMenus();
                    foreach ($sublinks as $sublink) {
                        $block['modules'][$i]['sublinks'][] = [
                            'name' => $sublink['name'],
                            'title' => $sublink['name'],
                            'url' => $xoops->url('modules/' . $dirname . '/' . $sublink['url']),
                        ];
                    }
                }
            }
            ++$i;
        }
    }

    // View Account
    array_unshift($block['modules'], [
        'name' => XoopsLocale::VIEW_ACCOUNT,
        'link' => $xoops->url('userinfo.php?uid=' . $xoops->user->getVar('uid')),
        'icon' => 'glyphicon glyphicon-user',
        'title' => XoopsLocale::VIEW_ACCOUNT,
    ]);

    // Edit Account
    array_unshift($block['modules'], [
        'name' => XoopsLocale::EDIT_ACCOUNT,
        'link' => $xoops->url('edituser.php'),
        'icon' => 'glyphicon glyphicon-pencil',
        'title' => XoopsLocale::EDIT_ACCOUNT,
    ]);

    // Administration Menu
    if ($xoops->isAdmin()) {
        array_unshift($block['modules'], [
            'name' => SystemLocale::ADMINISTRATION_MENU,
            'link' => $xoops->url('admin.php'),
            //'rel'  => 'external',
            'icon' => 'glyphicon glyphicon-wrench',
            'title' => SystemLocale::ADMINISTRATION_MENU,
        ]);
    }

    // Inbox
    $criteria = new CriteriaCompo(new Criteria('read_msg', 0));
    $criteria->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
    $pm_handler = $xoops->getHandlerPrivateMessage();
    $xoops->events()->triggerEvent('system.blocks.system_blocks.usershow', [&$pm_handler]);

    $name = XoopsLocale::INBOX;
    $class = '';
    if ($pm_count = $pm_handler->getCount($criteria)) {
        $name = XoopsLocale::INBOX . ' <span class="badge">' . $pm_count . '</span>';
        //$class = 'text-info';
    }

    array_push($block['modules'], [
        'name' => $name,
        'link' => $xoops->url('viewpmsg.php'),
        'icon' => 'glyphicon glyphicon-envelope',
        'class' => $class,
        'title' => XoopsLocale::INBOX,
    ]);

    // Logout
    array_push($block['modules'], [
        'name' => XoopsLocale::A_LOGOUT,
        'link' => $xoops->url('user.php?op=logout'),
        'icon' => 'glyphicon glyphicon-log-out',
        'title' => XoopsLocale::A_LOGOUT,
    ]);

    $block['active_url'] = \Xoops\Core\HttpRequest::getInstance()->getUrl();

    return $block;
}

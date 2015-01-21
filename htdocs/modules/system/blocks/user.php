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
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
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

    $block = array();
    $block['modules'] = array();

    $plugins = \Xoops\Module\Plugin::getPlugins('system');
    $i = 0;
    /* @var $plugin SystemPluginInterface */
    foreach ($plugins as $dirname => $plugin) {
        $menu = $plugin->userMenus();

        if (is_array($menu) && !empty($menu)) {
            $block['modules'][$i]['name'] = $menu['name'];
            $block['modules'][$i]['link'] = $xoops->url('modules/' . $dirname . '/' . $menu['link']);
            $block['modules'][$i]['image'] = $menu['image'];
            $block['modules'][$i]['icon'] = 'icon-tags';
            $block['modules'][$i]['dirname'] = $dirname;

            //todo, remove this hardcoded call
            if ($xoops->isModule() && $xoops->module->getVar('dirname') == $dirname && $plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'menus')) {
                if (method_exists($plugin, 'subMenus')) {
                    $sublinks = $plugin->subMenus();
                    foreach ($sublinks as $sublink) {
                        $block['modules'][$i]['sublinks'][] = array(
                            'name' => $sublink['name'],
                            'url'  => XOOPS_URL . '/modules/' . $dirname . '/' . $sublink['url']
                        );
                    }
                }
            }
            $i++;
        }

    }

    // View Account
    array_unshift($block['modules'], array(
        'name' => XoopsLocale::VIEW_ACCOUNT,
        'link' => $xoops->url('userinfo.php?uid=' . $xoops->user->getVar('uid')),
        'icon' => 'icon-user',
    ));

    // Edit Account
    array_unshift($block['modules'], array(
        'name' => XoopsLocale::EDIT_ACCOUNT,
        'link' => $xoops->url('edituser.php'),
        'icon' => 'icon-user',
    ));

    // Administration Menu
    if ($xoops->isAdmin()) {
        array_unshift($block['modules'], array(
            'name' => SystemLocale::ADMINISTRATION_MENU,
            'link' => $xoops->url('admin.php'),
            'rel'  => 'external',
            'icon' => 'icon-wrench',
        ));
    }

    // Inbox
    $criteria = new CriteriaCompo(new Criteria('read_msg', 0));
    $criteria->add(new Criteria('to_userid', $xoops->user->getVar('uid')));
    $pm_handler = $xoops->getHandlerPrivmessage();
    $xoops->preload()->triggerEvent('system.blocks.system_blocks.usershow', array(&$pm_handler));

    $name = XoopsLocale::INBOX;
    $class = '';
    if ($pm_count = $pm_handler->getCount($criteria)) {
        $name = XoopsLocale::INBOX . ' <strong>' . $pm_count . '</strong>';
        $class = 'highlight';
    }

    array_push($block['modules'], array(
        'name'  => $name,
        'link'  => $xoops->url('viewpmsg.php'),
        'icon'  => 'icon-envelope',
        'class' => $class,
    ));

    // Logout
    array_push($block['modules'], array(
        'name' => XoopsLocale::A_LOGOUT,
        'link' => $xoops->url('user.php?op=logout'),
        'icon' => 'icon-off',
    ));

    $block['active_url'] = \Xoops\Core\HttpRequest::getInstance()->getUrl();
    return $block;
}

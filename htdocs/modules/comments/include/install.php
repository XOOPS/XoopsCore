<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Handlers\XoopsModule;
use Xoops\Module\Plugin;

/**
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or greater (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

function xoops_module_install_comments(XoopsModule $module)
{
    $xoops = Xoops::getInstance();

    XoopsLoad::loadFile($xoops->path('modules/comments/class/helper.php'));
    $helper = Comments::getInstance();
    $plugins = Plugin::getPlugins('comments');
    foreach (array_keys($plugins) as $dirname) {
        $helper->insertModuleRelations($xoops->getModuleByDirname($dirname));
    }

    return true;
}

function xoops_module_pre_uninstall_comments(XoopsModule $module)
{
    $xoops = Xoops::getInstance();
    XoopsLoad::loadFile($xoops->path('modules/comments/class/helper.php'));
    $helper = Comments::getInstance();
    $plugins = Plugin::getPlugins('comments');
    foreach (array_keys($plugins) as $dirname) {
        $helper->deleteModuleRelations($xoops->getModuleByDirname($dirname));
    }

    return true;
}

function xoops_module_update_comments(XoopsModule $module, $prev_version)
{
    return xoops_module_install_comments($module);
}

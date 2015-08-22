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

/**
 * Install and Update debugbar module support routines
 *
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 */

/**
 * xoops_module_install_debugbar
 *
 * @param XoopsModule $module instance of our module
 *
 * @return mixed boolean false on error, integer file count on success
 */
function xoops_module_install_debugbar(XoopsModule $module)
{
    $xoops = Xoops::getInstance();
    // copy font-awesome font files to assets directory
    $dir = dirname(__DIR__).'/assets/fonts';
    $pattern = 'fontawesome-webfont.*';
    return $xoops->assets()->copyFileAssets($dir, $pattern, 'fonts');
}

/**
 * xoops_module_install_debugbar
 *
 * @param XoopsModule $module  instance of our module
 * @param integer     $version previously installed module version
 *
 * @return mixed boolean false on error, integer file count on success
 */
function xoops_module_update_debugbar(XoopsModule $module, $version)
{
    return xoops_module_install_debugbar($module);
}

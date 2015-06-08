<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\PreloadItem;

/**
 * Userconfigs
 *
 * @package   UserConfigs
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class UserconfigsPreload extends PreloadItem
{

    /**
     * add any module specific class map entries
     *
     * @param mixed $args not used
     *
     * @return void
     */
    public static function eventCoreIncludeCommonClassmaps($args)
    {
        $path = dirname(__DIR__);
        XoopsLoad::addMap(array(
            'userconfigs' => $path . '/class/helper.php',
        ));
    }

    /**
     * remove any userconfigs for module being uninstalled
     *
     * @param array $args index 0 is module object
     *
     * @return void
     */
    public static function eventOnModuleUninstall($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'userconfigs')) {
            Userconfigs::getInstance()->getHandlerConfig()->deleteConfigsByModule($module->getVar('mid'));
        }
    }
}

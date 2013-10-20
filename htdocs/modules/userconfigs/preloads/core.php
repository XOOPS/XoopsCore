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
 * Userconfigs
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Userconfigs core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class UserconfigsCorePreload extends XoopsPreloadItem
{
    static function initialize()
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'userconfigs' => $path . '/class/helper.php',
            'userconfigsconfighandler' => $path . '/class/config.php',
            'userconfigsitem' => $path . '/class/item.php',
            'userconfigsitemhandler' => $path . '/class/item.php',
            'userconfigsoption' => $path . '/class/option.php',
            'userconfigsoptionhandler' => $path . '/class/option.php',
        ));
    }

    static function eventOnModuleUninstall($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = Xoops_Module_Plugin::getPlugin($module->getVar('dirname'), 'userconfigs')) {
            Userconfigs::getInstance()->getHandlerConfig()->deleteConfigsByModule($module->getVar('mid'));
        }
    }
}
UserconfigsCorePreload::initialize();
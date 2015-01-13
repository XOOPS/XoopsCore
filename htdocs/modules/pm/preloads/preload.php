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
 * Private Messages preloads
 *
 * @package   Pm
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.4.0
 */
class PmPreload extends PreloadItem
{

    /**
     * core.pmlite.start
     *
     * @param array $args
     *
     * @return void
     */
    public static function eventCorePmliteStart($args)
    {
        header("location: ./modules/pm/pmlite.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
        exit();
    }

    /**
     * core.readpmsg.start
     *
     * @param array $args
     *
     * @return void
     */
    public static function eventCoreReadpmsgStart($args)
    {
        header("location: ./modules/pm/readpmsg.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
        exit();
    }

    /**
     * core.viewpmsg.start
     *
     * @param array $args
     *
     * @return void
     */
    public static function eventCoreViewpmsgStart($args)
    {
        header("location: ./modules/pm/viewpmsg.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
        exit();
    }

    /**
     * core.Class.smarty.xoops_plugins.xoinboxcount
     *
     * @param array $args
     *
     * @return void
     */
    public static function eventCoreClassSmartyXoops_pluginsXoinboxcount($args)
    {
        $args[0] = Xoops::getInstance()->getModuleHandler('message', 'pm');
    }

    /**
     * system.blocks.system_blocks.usershow
     *
     * @param array $args
     *
     * @return void
     */
    public static function eventSystemBlocksSystem_blocksUsershow($args)
    {
        $args[0] = Xoops::getInstance()->getModuleHandler('message', 'pm');
    }
}

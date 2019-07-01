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
 * plugins module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         plugins
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class PluginsPreload extends PreloadItem
{
    /**
     * listen for core.include.common.classmaps
     * add any module specific class map entries
     *
     * @param mixed $args not used
     *
     * @return void
     */
    public static function eventCoreIncludeCommonClassmaps($args)
    {
        $path = dirname(__DIR__);
        \XoopsLoad::addMap([
            'plugins' => $path . '/class/helper.php',
            'pluginsmanager' => $path . '/class/manager.php',
        ]);
    }

    /**
     * Filters an orders plugin listeners
     *
     * @param array $args
     */
    public static function eventCoreModulePluginGetPlugins($args)
    {
        //Don't run during uninstall, getActiveModule('plugins') won't work.
        if (\Xoops::getInstance()->getModuleByDirname('plugins')) {
            $args[0] = Plugins::getInstance()->getHandlerPlugin()->getActiveListenersByCaller($args[1]);
        }
    }

    /**
     * Updates plugins on module install
     *
     * @param $args
     */
    public static function eventSystemModuleInstall($args)
    {
        \Xoops::getInstance()->setActiveModules();
        //Adds new plugins if available and remove them if modules were deactivated
        PluginsManager::updatePlugins();
    }
}

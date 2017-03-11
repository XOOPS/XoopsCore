<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Module;

/**
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

class Plugin
{
    /**
     * @var array
     */
    protected static $plugins = array();

    /**
     * @param string $dirname    module dirname
     * @param string $pluginName plugin name i.e. system, menus, etc.
     * @param bool   $force      get plugin even if module is inactive
     *
     * @return bool|object plugin, or false if plugin does not exist
     */
    public static function getPlugin($dirname, $pluginName = 'system', $force = false)
    {
        $inactiveModules = false;
        if ($force) {
            $inactiveModules = array($dirname);
        }
        $available = self::getPlugins($pluginName, $inactiveModules);
        if (!in_array($dirname, array_keys($available))) {
            return false;
        }
        return $available[$dirname];
    }

    /**
     * @param string $pluginName
     * @param array|bool $inactiveModules
     *
     * @return mixed
     */
    public static function getPlugins($pluginName = 'system', $inactiveModules = false)
    {
        if (!isset(static::$plugins[$pluginName])) {
            static::$plugins[$pluginName] = array();
            $xoops = \Xoops::getInstance();

            //Load interface for this plugin
            if (!\XoopsLoad::loadFile($xoops->path("modules/{$pluginName}/class/plugin/interface.php"))) {
                return static::$plugins[$pluginName];
            }

            $dirnames = $xoops->getActiveModules();

            \Xoops::getInstance()->events()->triggerEvent('core.module.plugin.get.plugins', array(&$dirnames, $pluginName));

            if (is_array($inactiveModules)) {
                $dirnames = array_merge($dirnames, $inactiveModules);
            }
            foreach ($dirnames as $dirname) {
                if (\XoopsLoad::loadFile($xoops->path("modules/{$dirname}/class/plugin/{$pluginName}.php"))) {
                    $className = '\\' . ucfirst($dirname) . ucfirst($pluginName) . 'Plugin';
                    $interface = '\\' . ucfirst($pluginName) . 'PluginInterface';
                    $class = new $className($dirname);
                    if ($class instanceof $interface) {
                        static::$plugins[$pluginName][$dirname] = $class;
                    }
                }
            }
        }
        return static::$plugins[$pluginName];
    }

    /**
     * Clear cache of plugins
     * return void
     */
    public static function resetPluginsCache()
    {
        static::$plugins = array();
    }
}

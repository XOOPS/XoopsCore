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
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */
class PluginsManager
{
    /**
     * @return array returns an array of listeners in the form of listener=>caller
     */
    public static function getListeners()
    {
        $xoops = \Xoops::getInstance();
        $dirNames = $xoops->getActiveModules();
        $listeners = [];

        foreach ($dirNames as $listener) {
            foreach ($dirNames as $caller) {
                //Make sure to load the interface
                if (\XoopsLoad::loadFile($xoops->path("modules/{$caller}/class/plugin/interface.php"))) {
                    if (\XoopsLoad::loadFile($xoops->path("modules/{$listener}/class/plugin/{$caller}.php"))) {
                        $interfaceName = '\\' . ucfirst($caller) . 'PluginInterface';
                        if ($ref = new ReflectionClass($interfaceName)) {
                            if ($ref->implementsInterface($interfaceName)) {
                                $listeners[$listener][] = $caller;
                            }
                        }
                    }
                }
            }
        }

        return $listeners;
    }

    /**
     * Checks if new plugins are available and adds them to database
     *
     * @return bool
     */
    public static function updatePlugins()
    {
        $ret = true;
        $handler = Plugins::getInstance()->getHandlerPlugin();
        $xoops = \Xoops::getInstance();

        $listeners = $handler->getListeners();
        foreach ($listeners as $key => $name) {
            if (!$xoops->isActiveModule($key)) {
                $handler->deleteLC($key);
            }
        }
        $callers = $handler->getCallers();
        foreach ($callers as $key => $name) {
            if (!$xoops->isActiveModule($key)) {
                $handler->deleteLC($key);
            }
        }

        //Gets Listeners from file
        $plugins = self::getListeners();
        foreach ($plugins as $listener => $callers) {
            foreach ($callers as $caller) {
                if (!$object = $handler->getLC($listener, $caller)) {
                    if (!$handler->addNew($listener, $caller)) {
                        $ret = false;
                    }
                }
            }
        }

        return $ret;
    }
}

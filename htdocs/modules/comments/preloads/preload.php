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
use Xoops\Core\PreloadItem;
use Xoops\Module\Plugin;
use Xoops\Module\Plugin\ConfigCollector;

/**
 * Comments core preloads
 *
 * @copyright XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */
class CommentsPreload extends PreloadItem
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
        XoopsLoad::addMap([
            'comments' => $path . '/class/helper.php',
            'commentscommentrenderer' => $path . '/class/commentrenderer.php',
        ]);
    }

    public static function eventCoreFooterStart($args)
    {
        $helper = \Xoops::getModuleHelper('comments');
        $helper->renderView();
    }

    public static function eventSystemModuleUpdateConfigs(ConfigCollector $collector)
    {
        $helper = \Xoops::getModuleHelper('comments');
        if ($plugin = Plugin::getPlugin(
            $collector->module()->getVar('dirname'),
            'comments',
            true
        )) {
            $pluginConfigs = $helper->getPluginableConfigs();
            $collector->add($pluginConfigs);
        }
    }

    public static function eventSystemModuleInstall(XoopsModule $module)
    {
        $helper = \Xoops::getModuleHelper('comments');
        if ($plugin = Plugin::getPlugin($module->getVar('dirname'), 'comments', true)) {
            $helper::getInstance()->insertModuleRelations($module);
        }
    }

    /**
     * remove any comeents for module being uninstalled
     *
     * @param XoopsModule $module module object
     *
     * @return void
     */
    public static function eventSystemModuleUninstall(XoopsModule $module)
    {
        $helper = \Xoops::getModuleHelper('comments');
        if ($plugin = Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            $helper->deleteModuleRelations($module);
        }
    }

    public static function eventSystemPreferencesForm(XoopsModule $module)
    {
        $helper = \Xoops::getModuleHelper('comments');

        if ($plugin = Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            $helper->loadLanguage('main');
        }
    }
}

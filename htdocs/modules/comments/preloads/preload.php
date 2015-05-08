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
 * Comments core preloads
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
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
        XoopsLoad::addMap(array(
            'comments'                => $path . '/class/helper.php',
            'commentscommentrenderer' => $path . '/class/commentrenderer.php'
        ));
    }

    public static function eventCoreFooterStart($args)
    {
        $helper = Comments::getInstance();
        $helper->renderView();
    }

    public static function eventOnModuleUpdateConfigs($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        $configs =& $args[1];
        $helper = Comments::getInstance(); //init helper to load defines na language

        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments', true)) {
            $commentsConfigs = $helper->getPluginableConfigs();
            foreach ($commentsConfigs as $commentsConfig) {
                array_push($configs, $commentsConfig);
            }
        }
    }

    public static function eventOnModuleInstall($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments', true)) {
            Comments::getInstance()->insertModuleRelations($module);
        }
    }

    public static function eventOnModuleUninstall($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            Comments::getInstance()->deleteModuleRelations($module);
        }
    }

    public static function eventOnSystemPreferencesForm($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            Comments::getInstance()->loadLanguage('main');
        }
    }
}

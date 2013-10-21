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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Comments core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class CommentsCorePreload extends XoopsPreloadItem
{
    static function initialize()
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'comments'                => $path . '/class/helper.php',
            'commentscommentrenderer' => $path . '/class/commentrenderer.php'
        ));
    }

    static function eventCoreFooterStart($args)
    {
        $helper = Comments::getInstance();
        $helper->renderView();
    }

    static function eventOnModuleUpdateConfigs($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        $configs =& $args[1];
        $helper = Comments::getInstance(); //init helper to load defines na language

        if ($plugin = Xoops_Module_Plugin::getPlugin($module->getVar('dirname'), 'comments', true)) {
            $commentsConfigs = $helper->getPluginableConfigs();
            foreach ($commentsConfigs as $commentsConfig) {
                array_push($configs, $commentsConfig);
            }
        }
    }

    static function eventOnModuleInstall($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = Xoops_Module_Plugin::getPlugin($module->getVar('dirname'), 'comments', true)) {
            Comments::getInstance()->insertModuleRelations($module);
        }
    }

    static function eventOnModuleUninstall($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = Xoops_Module_Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            Comments::getInstance()->deleteModuleRelations($module);
        }
    }

    static function eventOnSystemPreferencesForm($args)
    {
        /* @var $module XoopsModule */
        $module = $args[0];
        if ($plugin = Xoops_Module_Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            Comments::getInstance()->loadLanguage('main');
        }
    }
}
CommentsCorePreload::initialize();
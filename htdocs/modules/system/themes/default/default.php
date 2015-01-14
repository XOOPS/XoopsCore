<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


/*
 * Xoops Cpanel default GUI class
 *
 * @copyright   The XOOPS project http://sf.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     system
 * @usbpackage  GUI
 * @since       2.4
 * @author      Mamba       <mambax7@gmail.com>
 * @author      Mojtabajml  <jamali.mojtaba@gmail.com>
 * @author      Voltan      <djvoltan@gmail.com>
 * @author      BitC3R0     <BitC3R0@gmail.com>
 * @author      trabis      <lusopoemas@gmail.com>
 * @version     1.2
 * @version     $Id$
 */

class XoopsGuiDefault
{

    function header()
    {
        $xoops = Xoops::getInstance();
        $xoops->loadLocale('system');

        $xoops->theme()->addBaseStylesheetAssets('@jqueryuicss');
        $xoops->theme()->addStylesheet('media/xoops/css/moduladmin.css');
        $xoops->theme()->addStylesheet(XOOPS_ADMINTHEME_URL . '/default/css/style.css');

        $xoops->theme()->addBaseScriptAssets('@jquery');
        // bootstrap has to come before jquery.ui or dialog close buttons are blank
        $xoops->theme()->addBaseScriptAssets('@bootstrap');
        $xoops->theme()->addBaseScriptAssets('@jqueryui');
        $xoops->theme()->addBaseScriptAssets('@jgrowl');
        // ddsmoothmenu
        $xoops->theme()->addScript(XOOPS_ADMINTHEME_URL . '/default/js/ddsmoothmenu.js');
        $xoops->theme()->addScript(XOOPS_ADMINTHEME_URL . '/default/js/tooltip.js');

        $quick = array();
        $quick[] = array('title' => SystemLocale::CONTROL_PANEL, 'link' => XOOPS_URL . '/admin.php');
        $quick[] = array('title' => XoopsLocale::HOME_PAGE, 'link' => XOOPS_URL);
        $quick[] = array('title' => DefaultThemeLocale::XOOPS_NEWS, 'link' => XOOPS_URL . '/admin.php?xoopsorgnews=1');
        $quick[] = array('title' => 'separator');
        $quick[] = array('title' => XoopsLocale::A_LOGOUT, 'link' => XOOPS_URL . '/user.php?op=logout');
        $xoops->tpl()->assign('quick_menu', $quick);

        XoopsLoad::load('module', 'system');
        XoopsLoad::load('extension', 'system');
        $system_module = new SystemModule();
        $system_extension = new SystemExtension();

        $adminmenu = null;
        include __DIR__ . '/menu.php';
        if (!$xoops->isModule() || 'system' == $xoops->module->getVar('dirname', 'n')) {
            $modpath = XOOPS_URL . '/admin.php';
            $modname = DefaultThemeLocale::SYSTEM_OPTIONS;
            $modid = 1;
            $moddir = 'system';

            $mod_options = $adminmenu;
            foreach (array_keys($mod_options) as $item) {
                $mod_options[$item]['link'] = empty($mod_options[$item]['absolute'])
                    ? XOOPS_URL . '/modules/' . $moddir . '/' . $mod_options[$item]['link']
                    : $mod_options[$item]['link'];
                $mod_options[$item]['icon'] = empty($mod_options[$item]['icon']) ? ''
                    : XOOPS_ADMINTHEME_URL . '/default/' . $mod_options[$item]['icon'];
                unset($mod_options[$item]['icon_small']);
            }

        } else {
            $moddir = $xoops->module->getVar('dirname', 'n');
            $modpath = XOOPS_URL . '/modules/' . $moddir;
            $modname = $xoops->module->getVar('name');
            $modid = $xoops->module->getVar('mid');

            $mod_options = $xoops->module->getAdminMenu();
            foreach (array_keys($mod_options) as $item) {
                $mod_options[$item]['link'] = empty($mod_options[$item]['absolute'])
                    ? XOOPS_URL . "/modules/{$moddir}/" . $mod_options[$item]['link'] : $mod_options[$item]['link'];
                if ( XoopsLoad::fileExists($xoops->path("/media/xoops/images/icons/32/" . $mod_options[$item]['icon']) ) ) {
                    $mod_options[$item]['icon'] = $xoops->url("/media/xoops/images/icons/32/" . $mod_options[$item]['icon']);
                } else {
                    $mod_options[$item]['icon'] = $xoops->url("/modules/" . $xoops->module->dirname() . "/icons/32/" . $mod_options[$item]['icon']);
                }
            }
        }
        $xoops->tpl()->assign('mod_options', $mod_options);
        $xoops->tpl()->assign('modpath', $modpath);
        $xoops->tpl()->assign('modname', $modname);
        $xoops->tpl()->assign('modid', $modid);
        $xoops->tpl()->assign('moddir', $moddir);

        // Modules list
        $module_list = $system_module->getModuleList();
        $xoops->tpl()->assign('module_menu', $module_list);
        unset($module_list);

        // Extensions list
        $extension_list = $system_extension->getExtensionList();
        $xoops->tpl()->assign('extension_menu', $extension_list);
        unset($extension_list);

        $extension_mod = $system_extension->getExtension( $moddir );
        $xoops->tpl()->assign('extension_mod', $extension_mod);

        // add preferences menu
        $menu = array();

        $OPT = array();

        $menu[] = array(
            'link' => XOOPS_URL . '/modules/system/admin.php?fct=preferences', 'title' => XoopsLocale::PREFERENCES,
            'absolute' => 1, 'url' => XOOPS_URL . '/modules/system/', 'options' => $OPT
        );
        $menu[] = array('title' => 'separator');

        // Module adminmenu
        if ($xoops->isModule() && $xoops->module->getVar('dirname') != 'system') {

            if ($xoops->module->getInfo('system_menu')) {
                //$xoops->theme()->addStylesheet('modules/system/css/menu.css');

                $xoops->module->loadAdminMenu();
                // Get menu tab handler
                /* @var $menu_handler SystemMenuHandler */
                $menu_handler = $xoops->getModuleHandler('menu', 'system');
                // Define top navigation
                $menu_handler->addMenuTop(XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoops->module->getVar('mid', 'e'), XoopsLocale::PREFERENCES);
                if ($xoops->module->getInfo('extension')) {
                    $menu_handler->addMenuTop(XOOPS_URL . "/modules/system/admin.php?fct=extensions&amp;op=update&amp;module=" . $xoops->module->getVar('dirname', 'e'), XoopsLocale::A_UPDATE);
                } else {
                    $menu_handler->addMenuTop(XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&amp;op=update&amp;module=" . $xoops->module->getVar('dirname', 'e'), XoopsLocale::A_UPDATE);
                }
                if ($xoops->module->getInfo('blocks')) {
                    $menu_handler->addMenuTop(XOOPS_URL . "/modules/system/admin.php?fct=blocksadmin&amp;op=list&amp;filter=1&amp;selgen=" . $xoops->module->getVar('mid', 'e') . "&amp;selmod=-2&amp;selgrp=-1&amp;selvis=-1", XoopsLocale::BLOCKS);
                }
                if ($xoops->module->getInfo('hasMain')) {
                    $menu_handler->addMenuTop(XOOPS_URL . "/modules/" . $xoops->module->getVar('dirname', 'e') . "/", SystemLocale::GO_TO_MODULE);
                }
                // Define main tab navigation
                $i = 0;
                $current = $i;
                foreach ($xoops->module->adminmenu as $menu) {
                    if (stripos($_SERVER['REQUEST_URI'], $menu['link']) !== false) {
                        $current = $i;
                    }
                    $menu_handler->addMenuTabs( $xoops->url('modules/' . $xoops->module->getVar('dirname') . '/' . $menu['link']), $menu['title']);
                    $i++;
                }
                if ($xoops->module->getInfo('help')) {
                    if (stripos($_SERVER['REQUEST_URI'], 'admin/' . $xoops->module->getInfo('help')) !== false) {
                        $current = $i;
                    }
                    $menu_handler->addMenuTabs('../../system/help.php?mid=' . $xoops->module->getVar('mid', 's') . '&amp;' . $xoops->module->getInfo('help'), XoopsLocale::HELP);
                }

                // Display navigation tabs
                $xoops->tpl()->assign('xo_system_menu', $menu_handler->render($current, false));
            }
        }

    }
}

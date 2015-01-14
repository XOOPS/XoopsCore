<?php
/**
 * XOOPS Closed Site
 *
 * Temporary solution for "site closed" status
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         include
 * @since           2.0.17
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

$xoops = Xoops::getInstance();

$allowed = false;
if ($xoops->isUser()) {
    foreach ($xoops->user->getGroups() as $group) {
        if (in_array($group, $xoops->getConfig('closesite_okgrp')) || XOOPS_GROUP_ADMIN == $group) {
            $allowed = true;
            break;
        }
    }
} else {
    if (!empty($_POST['xoops_login'])) {
        include_once $xoops->path('include/checklogin.php');
        exit();
    }
}

if (!$allowed) {
    $xoopsThemeFactory = null;
    $xoopsThemeFactory = new XoopsThemeFactory();
    $xoopsThemeFactory->allowedThemes = $xoops->getConfig('theme_set_allowed');
    $xoopsThemeFactory->defaultTheme = $xoops->getConfig('theme_set');
    $xoops->setTheme($xoopsThemeFactory->createInstance(array('plugins' => array())));
    unset($xoopsThemeFactory);
    $xoops->theme()->addScript('/include/xoops.js', array('type' => 'text/javascript'));
    $xoops->setTpl($xoops->theme()->template);
    $xoops->tpl()->assign(array(
                           'xoops_theme' => $xoops->getConfig('theme_set'),
                           'xoops_imageurl' => XOOPS_THEME_URL . '/' . $xoops->getConfig('theme_set') . '/',
                           'xoops_themecss' => $xoops->getCss($xoops->getConfig('theme_set')),
                           'xoops_requesturi' => htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES),
                           'xoops_sitename' => htmlspecialchars($xoops->getConfig('sitename'), ENT_QUOTES),
                           'xoops_slogan' => htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES),
                           'xoops_dirname' => $xoops->isModule() ? $xoops->module->getVar('dirname') : 'system',
                           'xoops_banner' => $xoops->getConfig('banners') ? $xoops->getBanner() : '&nbsp;',
                           'xoops_pagetitle' => $xoops->isModule() ? $xoops->module->getVar('name') : htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES),
                           'lang_login' => XoopsLocale::A_LOGIN, 'lang_username' => XoopsLocale::C_USERNAME, 'lang_password' => XoopsLocale::C_PASSWORD,
                           'lang_siteclosemsg' => $xoops->getConfig('closesite_text')
                      ));
    //todo check if we can use $xoops->getConfig() instead
    $config_handler = $xoops->getHandlerConfig();
    $criteria = new CriteriaCompo(new Criteria('conf_modid', 1));
    $criteria->add(new Criteria('conf_catid'));
    $config = $config_handler->getConfigs($criteria, true);
    foreach (array_keys($config) as $i) {
        $name = $config[$i]->getVar('conf_name', 'n');
        $value = $config[$i]->getVar('conf_value', 'n');
        if (substr($name, 0, 5) == 'meta_') {
            $xoops->tpl()->assign("xoops_$name", htmlspecialchars($value, ENT_QUOTES));
        } else {
            // prefix each tag with 'xoops_'
            $xoops->tpl()->assign("xoops_$name", $value);
        }
    }
    $xoops->tpl()->debugging = false;
    $xoops->tpl()->debugging_ctrl = 'none';
    $xoops->tpl()->caching = 0;
    $xoops->tpl()->display('module:system/system_siteclosed.tpl');
    exit();
}
unset($allowed, $group);

return true;

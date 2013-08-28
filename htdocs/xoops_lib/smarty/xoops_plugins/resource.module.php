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

/**
 * @param $tpl_name
 * @param $tpl_source
 * @param $smarty
 * @return bool
 */
function smarty_resource_module_source($tpl_name, &$tpl_source, &$smarty)
{
    if (!$tpl = smarty_resource_module_tplinfo($tpl_name)) {
        return false;
    }

    $fp = fopen($tpl, 'r');
    $filesize = filesize($tpl);
    $tpl_source = ($filesize > 0) ? fread($fp, $filesize) : '';
    fclose($fp);

    return true;
}

function smarty_resource_module_timestamp($tpl_name, &$tpl_timestamp, &$smarty)
{
    if (!$tpl = smarty_resource_module_tplinfo($tpl_name)) {
        return false;
    }
    $tpl_timestamp = filemtime($tpl);
    return true;
}

function smarty_resource_module_secure($tpl_name, &$smarty)
{
    // assume all templates are secure
    return true;
}

function smarty_resource_module_trusted($tpl_name, &$smarty)
{
    // not used for templates
}

function smarty_resource_module_tplinfo($tpl_name)
{
    static $cache = array();
    $xoops = Xoops::getInstance();
    $tpl_info = $xoops->getTplInfo($tpl_name);
    $tpl_name = $tpl_info['tpl_name'];
    $dirname = $tpl_info['module'];
    $file = $tpl_info['file'];

    if (isset($cache[$tpl_name])) {
        return $cache[$tpl_name];
    }


    $theme_set = $xoops->getConfig('theme_set') ? $xoops->getConfig('theme_set') : 'default';
    if (!file_exists($file_path = $xoops->path("themes/{$theme_set}/modules/{$dirname}/{$file}"))) {
        $file_path = $xoops->path("modules/{$dirname}/templates/{$file}");
    }
    return $cache[$tpl_name] = $file_path;
}
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
 * xoModuleIcons32 Smarty compiler plug-in
 *
 * @copyright      The XOOPS project http://www.xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author         Andricq Nicolas (AKA MusS)
 * @since          2.5.2
 * @version        $Id$
 */

function smarty_compiler_xoModuleIcons32($argStr, &$smarty)
{
    $xoops = Xoops::getInstance();
    $icons = $xoops->getModuleConfig('typeicons', 'system');
    if ($icons == '') {
        $icons = 'default';
    }

    if (XoopsLoad::fileExists($xoops->path('media/xoops/images/icons/32/' . $icons . '/index.html'))) {
        $url = $xoops->url('media/xoops/images/icons/32/' . $icons . '/' . $argStr);
    } else {
        if (XoopsLoad::fileExists($xoops->path('modules/system/assets/images/icons/default/' . $argStr))) {
            $url = $xoops->url('modules/system/assets/images/icons/default/' . $argStr);
        } else {
            $url = $xoops->url('modules/system/assets/images/icons/default/xoops/xoops.png');
        }
    }
    return "\necho '" . addslashes($url) . "';";
}

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
 * xoModuleIcons16 Smarty compiler plug-in
 *
 * @copyright  2000-2020 XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Andricq Nicolas (AKA MusS)
 * @since       2.5.2
 * @param mixed $params
 */
function smarty_compiler_xoModuleIcons16($params, Smarty $smarty)
{
    $xoops = Xoops::getInstance();
    $arg = reset($params);
    $ico = trim($arg, " '\"\t\n\r\0\x0B");

    if (XoopsLoad::fileExists($xoops->path('media/xoops/images/icons/16/index.html'))) {
        $url = $xoops->url('media/xoops/images/icons/16/' . $ico);
    } else {
        if (XoopsLoad::fileExists($xoops->path('modules/system/images/icons/default/' . $ico))) {
            $url = $xoops->url('modules/system/images/icons/default/' . $ico);
        } else {
            $url = $xoops->url('modules/system/images/icons/default/xoops/xoops2.png');
        }
    }

    return "<?php echo '" . addslashes($url) . "'; ?>";
}

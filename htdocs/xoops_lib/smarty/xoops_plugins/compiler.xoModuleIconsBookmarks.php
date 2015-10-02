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
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Andricq Nicolas (AKA MusS)
 * @since       2.5.2
 */

function smarty_compiler_xoModuleIconsBookmarks($argStr, &$smarty)
{
    $xoops = Xoops::getInstance();

    if (XoopsLoad::fileExists($xoops->path('media/xoops/images/icons/bookmarks/index.html'))) {
        $url = $xoops->url('media/xoops/images/icons/bookmarks/' . $argStr);
    } else {
        if (XoopsLoad::fileExists($xoops->path('modules/system/images/icons/default/' . $argStr))) {
            $url = $xoops->url('modules/system/images/icons/default/' . $argStr);
        } else {
            $url = $xoops->url('modules/system/images/icons/default/xoops/xoops.png');
        }
    }
    return "\necho '" . addslashes($url) . "';";
}

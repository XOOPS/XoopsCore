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
 * Menus preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class MenusPreload extends PreloadItem
{
    public static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'menus' => $path . '/class/helper.php',
            'menusbuilder' => $path . '/class/builder.php',
            'menusdecorator' => $path . '/class/decorator.php',
        ));
    }
}

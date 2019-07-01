<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Request;
use Xoops\Core\PreloadItem;

/**
 * Images core preloads
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
class ImagesPreload extends PreloadItem
{
    public static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(__DIR__);
        XoopsLoad::addMap([
            'images' => $path . '/class/helper.php',
        ]);
    }

    public static function eventCoreClassXoopsformFormdhtmltextareaCodeicon($args)
    {
        /* @var $dhtml Xoops\Form\DhtmlTextArea */
        $dhtml = $args[1];
        $args[0] .= "<img src='" . \XoopsBaseConfig::get('url') . "/images/image.gif' alt='" . XoopsLocale::INSIDE_IMAGE . "' title='" . XoopsLocale::INSIDE_IMAGE . "' onclick='openWithSelfMain(\"" . \XoopsBaseConfig::get('url') . "/modules/images/imagemanager.php?target={$dhtml->getName()}\",\"imgmanager\",400,430);'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
    }

    public static function eventCoreImage($args)
    {
        $uri = '';
        foreach (Request::get() as $k => $v) {
            $uri .= urlencode($k) . '=' . urlencode($v) . '&';
        }
        Xoops::getInstance()->redirect("modules/images/image.php?{$uri}", 0);
    }

    public static function eventCoreImagemanager($args)
    {
        $uri = '';
        foreach (Request::get() as $k => $v) {
            $uri .= urlencode($k) . '=' . urlencode($v) . '&';
        }
        Xoops::getInstance()->redirect("modules/images/imagemanager.php?{$uri}", 0);
    }
}

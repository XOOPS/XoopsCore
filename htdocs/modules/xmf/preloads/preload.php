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
 * XMF module preloads
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package   Xmf
 * @since     0.1
 */
class XmfPreload extends PreloadItem
{
    public static function eventCoreIncludeCommonEnd($args)
    {
        if (file_exists($filename = \XoopsBaseConfig::get('root-path') . '/modules/xmf/include/bootstrap.php')) {
            include_once $filename;
        }
    }
}

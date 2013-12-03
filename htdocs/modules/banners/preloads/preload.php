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
 * banners module preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 */
class BannersPreload extends PreloadItem
{
    public static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'banners' => $path . '/class/helper.php',
        ));
    }
    public static function eventCoreBannerDisplay($args)
    {
        require_once dirname(dirname(__FILE__)) . '/class/bannerrender.php';
        $render = new BannerRender();
        $args[0] = $render->displayBanner();
    }
}

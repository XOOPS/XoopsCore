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
 * banners module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Gr�gory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Banners core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Mage Gr�gory (AKA Mage)
 */
class BannersCorePreload extends XoopsPreloadItem
{
    static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'banners' => $path . '/class/helper.php',
        ));
    }
    static public function eventCoreBannerDisplay($args)
    {
        require_once dirname(dirname(__FILE__)) . '/class/bannerrender.php';
        $render = new BannerRender();
        $args[0] = $render->displayBanner();
    }
}
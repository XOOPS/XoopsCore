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
 * Page core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         page
 * @since           2.6.0
 * @author          DuGris (aka Laurent JEN)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class PageCorePreload extends XoopsPreloadItem
{
    static function initialize()
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'page' => $path . '/class/helper.php',
            'pagegrouppermhandler' => $path . '/class/groupperm.php',
            'pagepage_content' => $path . '/class/page_content.php',
            'pagepage_contenthandler' => $path . '/class/page_content.php',
            'pagepage_rating' => $path . '/class/page_rating.php',
            'pagepage_ratinghandler' => $path . '/class/page_rating.php',
            'pagepage_related' => $path . '/class/page_related.php',
            'pagepage_relatedhandler' => $path . '/class/page_related.php',
            'pagepage_related_link' => $path . '/class/page_related_link.php',
            'pagepage_related_linkhandler' => $path . '/class/page_related_link.php',
        ));
    }
}
PageCorePreload::initialize();

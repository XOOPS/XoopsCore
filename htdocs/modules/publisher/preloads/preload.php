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
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         publisher
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

/**
 * Publisher core preloads
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
class PublisherPreload extends PreloadItem
{
    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonClassmaps($args): void
    {
        $path = dirname(__DIR__);
        //        XoopsLoad::addMap(array(
        //            'publishermetagen' => $path . '/class/metagen.php',
        //            'publisher' => $path . '/class/helper.php',
        //            'publisherutils' => $path . '/class/utils.php',
        //            'publisherblockform' => $path . '/class/blockform.php',
        //        ));
    }
}

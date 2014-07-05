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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         publisher
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

/**
 * Publisher core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
class PublisherCorePreload extends XoopsPreloadItem
{
    static function eventCoreIncludeCommonEnd($args)
    {
        $path = dirname(__DIR__);
        XoopsLoad::addMap(array(
            'publishermetagen' => $path . '/class/metagen.php',
            'publishersession'  => $path . '/class/session.php',
            'publisher' => $path . '/class/helper.php',
            'publisherrequest'  => $path . '/class/request.php',
            'publisherutils' => $path . '/class/utils.php',
            'publisherblockform' => $path . '/class/blockform.php',
        ));
    }
}

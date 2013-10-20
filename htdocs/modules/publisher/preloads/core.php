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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         publisher
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Publisher core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class PublisherCorePreload extends XoopsPreloadItem
{
    static function initialize()
    {
        $path = dirname(dirname(__FILE__));
        XoopsLoad::addMap(array(
            'publisherblockform' => $path . '/class/blockform.php',
            'publishercategory' => $path . '/class/category.php',
            'publishercategoryhandler' => $path . '/class/category.php',
            'publisherfile' => $path . '/class/file.php',
            'publisherfilehandler' => $path . '/class/file.php',
            'publisherformdatetime' => $path . '/class/formdatetime.php',
            'publishergrouppermhandler' => $path . '/class/groupperm.php',
            'publisher' => $path . '/class/helper.php',
            'publisheritem' => $path . '/class/item.php',
            'publisheritemhandler' => $path . '/class/item.php',
            'publishermetagen' => $path . '/class/metagen.php',
            'publisherbaseobjecthandler' => $path . '/class/mimetype.php',
            'publishermimetype' => $path . '/class/mimetype.php',
            'publishermimetypehandler' => $path . '/class/mimetype.php',
            'publisherpermissionhandler' => $path . '/class/permission.php',
            'publisherrating' => $path . '/class/rating.php',
            'publisherratinghandler' => $path . '/class/rating.php',
            'publisherrequest'  => $path . '/class/request.php',
            'publisherfilterinput'  => $path . '/class/request.php',
            'publishersession'  => $path . '/class/session.php',
            'publisherutils' => $path . '/class/utils.php',
        ));
    }
}
PublisherCorePreload::initialize();

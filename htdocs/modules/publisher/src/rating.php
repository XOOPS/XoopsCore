<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

class PublisherRating extends XoopsObject
{
    /**
     * constructor
     */
    public function __construct()
    {
        $this->initVar("ratingid", XOBJ_DTYPE_INT, null, false);
        $this->initVar("itemid", XOBJ_DTYPE_INT, null, false);
        $this->initVar("uid", XOBJ_DTYPE_INT, null, false);
        $this->initVar("rate", XOBJ_DTYPE_INT, null, false);
        $this->initVar("ip", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("date", XOBJ_DTYPE_INT, null, false);
    }
}

class PublisherRatingHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'publisher_rating', 'PublisherRating', 'ratingid', 'itemid');
    }
}

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
 * XOOPS Kernel Class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.6.0
 * @author          Gregory Mage (AKA Mage)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsObjectHandler;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

class XoopsRanks extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('rank_id', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('rank_title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('rank_min', XOBJ_DTYPE_INT, null, false, 8);
        $this->initVar('rank_max', XOBJ_DTYPE_INT, null, false, 8);
        $this->initVar('rank_special', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('rank_image', XOBJ_DTYPE_TXTBOX, null, false);
    }
}

class XoopsRanksHandler extends XoopsPersistableObjectHandler
{

    /**
     * @param XoopsConnection $db
     */
    public function __construct(XoopsConnection $db)
    {
        parent::__construct($db, 'ranks', 'XoopsRanks', 'rank_id', 'rank_title');
    }

}
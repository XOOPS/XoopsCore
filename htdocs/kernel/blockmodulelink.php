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
 * XOOPS Kernel Class
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.6.0
 * @author          Gregory Mage (AKA Mage)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class XoopsBlockmodulelink extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('block_id', XOBJ_DTYPE_INT);
        $this->initVar('module_id', XOBJ_DTYPE_INT);
    }
}

class XoopsBlockmodulelinkHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|Connection $db
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'block_module_link', 'XoopsBlockmodulelink', 'block_id', 'module_id');
    }
}

<?php
/**
 * XOOPS Kernel Class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * @package         kernel
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 */
class XoopsCachemodelObject extends XoopsObject
{
    /**
     * Constructor
     */
    function __construct()
    {
        $this->initVar('cache_key', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cache_data', XOBJ_DTYPE_SOURCE);
        $this->initVar('cache_expires', XOBJ_DTYPE_INT);
    }
}

/**
 * @package         kernel
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 */
class XoopsCachemodelHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'cache_model', 'XoopsCachemodelObject', 'cache_key', 'cache_data');
    }
}

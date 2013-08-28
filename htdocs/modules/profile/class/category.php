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
 * Extended User Profile
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class ProfileCategory extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('cat_id', XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT);
    }
}

class ProfileCategoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsConnection $db
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'profile_category', 'profilecategory', 'cat_id', 'cat_title');
    }
}
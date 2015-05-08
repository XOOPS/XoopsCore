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
/**
 * tdmcreate module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         tdmcreate
 * @since           2.6.0
 * @author          TDM Xoops (AKA Developers)
 * @version         $Id: fieldnull.php 10665 2012-12-27 10:14:15Z timgno $
 */
/**
 * Class TDMCreateFieldnull
 */
class TDMCreateFieldnull extends XoopsObject
{ 
	/**
     * Constructor
     */
	public function __construct()
	{
		$this->initVar('fieldnull_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldnull_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldnull_value', XOBJ_DTYPE_TXTBOX);
	}
}
/**
 * Class TDMCreateFieldnullHandler
 */
class TDMCreateFieldnullHandler extends XoopsPersistableObjectHandler 
{
    /**
     * @param null|Connection $db
     */
	public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'tdmcreate_fieldnull', 'tdmcreatefieldnull', 'fieldnull_id', 'fieldnull_name');
    }
}

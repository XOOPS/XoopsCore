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
 * @version         $Id: fieldattributes.php 10665 2012-12-27 10:14:15Z timgno $
 */

class TDMCreateFieldattributes extends XoopsObject
{ 
	/**
     * Constructor
     */
	public function __construct()
	{
		$this->initVar('fieldattribute_id', XOBJ_DTYPE_INT);
        $this->initVar('fieldattribute_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('fieldattribute_value', XOBJ_DTYPE_TXTBOX);	       		
	}
}

class TDMCreateFieldattributesHandler extends XoopsPersistableObjectHandler 
{
    /**
     * @param null|Connection $db
     */
    public function __construct(Connection $db = null) 
    {
        parent::__construct($db, 'tdmcreate_fieldattributes', 'tdmcreatefieldattributes', 'fieldattributes_id', 'fieldattributes_name');
    }
}
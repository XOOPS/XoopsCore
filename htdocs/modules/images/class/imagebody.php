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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Images
 * @author
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class ImagesImagebody extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('image_id', XOBJ_DTYPE_INT, null, false, 8);
        $this->initVar('image_body', XOBJ_DTYPE_SOURCE, null, true);
    }
}

class ImagesImagebodyHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param XoopsConnection|null $db {@link XoopsConnection}
     */
    public function __construct(XoopsConnection $db = null)
    {
        parent::__construct($db, 'imagebody', 'ImagesImagebody', 'image_id', 'image_body');
    }
}
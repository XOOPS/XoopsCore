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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Kernel\XoopsObject;

/**
 * A group permission
 *
 * These permissions are managed through a {@link XoopsGroupPermHandler} object
 *
 * @package     kernel
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 */
class XoopsGroupPerm extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('gperm_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_groupid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_itemid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_modid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('gperm_name', XOBJ_DTYPE_OTHER, null, false);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_id($format = '')
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_groupid($format = '')
    {
        return $this->getVar('gperm_groupid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_itemid($format = '')
    {
        return $this->getVar('gperm_itemid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_modid($format = '')
    {
        return $this->getVar('gperm_modid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function gperm_name($format = '')
    {
        return $this->getVar('gperm_name', $format);
    }

}

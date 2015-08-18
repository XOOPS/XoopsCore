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
 * a group of users
 *
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @author Kazumi Ono <onokazu@xoops.org>
 * @package kernel
 */
class XoopsGroup extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('groupid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('group_type', XOBJ_DTYPE_OTHER, null, false);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('groupid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function groupid($format = '')
    {
        return $this->getVar('groupid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function name($format = '')
    {
        return $this->getVar('name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function description($format = '')
    {
        return $this->getVar('description', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function group_type($format = '')
    {
        return $this->getVar('group_type', $format);
    }

}

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
 * @since           2.6.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;

/**
 * membership of a user in a group
 *
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package   kernel
 */
class XoopsMembership extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('linkid', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('groupid', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('uid', Dtype::TYPE_INTEGER, null, false);
    }

    /**
    * getter for id generic key
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->linkid($format);
    }

    /**
    * getter for linkid field
     * @param string $format
     * @return mixed
     */
    public function linkid($format = '')
    {
        return $this->getVar('linkid', $format);
    }

    /**
    * getter for uid field
     * @param string $format
     * @return mixed
     */
    public function uid($format = '')
    {
        return $this->getVar('uid', $format);
    }

}

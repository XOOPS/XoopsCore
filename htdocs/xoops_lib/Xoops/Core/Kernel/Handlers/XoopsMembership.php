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
 * @category  Xoops\Core\Kernel\Handlers\XoopsMembership
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
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
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function id($format = Dtype::FORMAT_NONE)
    {
        return $this->linkid($format);
    }

    /**
     * getter for linkid field
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function linkid($format = '')
    {
        return $this->getVar('linkid', $format);
    }

    /**
     * getter for uid field
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function uid($format = '')
    {
        return $this->getVar('uid', $format);
    }
}

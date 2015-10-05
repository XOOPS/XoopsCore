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

use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;

/**
 * A group permission
 *
 * These permissions are managed through a XoopsGroupPermHandler object
 *
 * @category  Xoops\Core\Kernel\Handlers\XoopsGroupPerm
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsGroupPerm extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('gperm_id', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('gperm_groupid', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('gperm_itemid', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('gperm_modid', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('gperm_name', Dtype::TYPE_OTHER, null, false);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function id($format = Dtype::FORMAT_NONE)
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function gperm_id($format = '')
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function gperm_groupid($format = '')
    {
        return $this->getVar('gperm_groupid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function gperm_itemid($format = '')
    {
        return $this->getVar('gperm_itemid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function gperm_modid($format = '')
    {
        return $this->getVar('gperm_modid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function gperm_name($format = '')
    {
        return $this->getVar('gperm_name', $format);
    }
}

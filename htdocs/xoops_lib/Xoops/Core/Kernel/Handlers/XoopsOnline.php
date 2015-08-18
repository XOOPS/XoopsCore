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
 * Online object
 *
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright copyright (c) 2000 XOOPS.org
 * @package   kernel
 */
class XoopsOnline extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('online_uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('online_uname', XOBJ_DTYPE_TXTBOX, null, true);
        $this->initVar('online_updated', XOBJ_DTYPE_INT, null, true);
        $this->initVar('online_module', XOBJ_DTYPE_INT, null, true);
        $this->initVar('online_ip', XOBJ_DTYPE_TXTBOX, null, true);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->online_uid($format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_uid($format = 'n')
    {
        return $this->getVar('online_uid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_uname($format = '')
    {
        return $this->getVar('online_uname', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_updated($format = '')
    {
        return $this->getVar('online_updated', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_module($format = '')
    {
        return $this->getVar('online_module', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function online_ip($format = '')
    {
        return $this->getVar('online_ip', $format);
    }
}

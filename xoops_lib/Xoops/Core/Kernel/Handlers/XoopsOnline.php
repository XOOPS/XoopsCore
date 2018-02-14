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
 * Online object
 *
 * @category  Xoops\Core\Kernel\Handlers\XoopsOnline
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsOnline extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('online_uid', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('online_uname', Dtype::TYPE_TEXT_BOX, null, true);
        $this->initVar('online_updated', Dtype::TYPE_INTEGER, null, true);
        $this->initVar('online_module', Dtype::TYPE_INTEGER, null, true);
        $this->initVar('online_ip', Dtype::TYPE_TEXT_BOX, null, true);
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
        return $this->online_uid($format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function online_uid($format = Dtype::FORMAT_NONE)
    {
        return $this->getVar('online_uid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function online_uname($format = '')
    {
        return $this->getVar('online_uname', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function online_updated($format = '')
    {
        return $this->getVar('online_updated', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function online_module($format = '')
    {
        return $this->getVar('online_module', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function online_ip($format = '')
    {
        return $this->getVar('online_ip', $format);
    }
}

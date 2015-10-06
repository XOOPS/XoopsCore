<?php
/**
 * XOOPS kernel class
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
 * A Template Set File
 *
 * @category  Xoops\Core\Kernel\Handlers\XoopsTplSet
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsTplSet extends XoopsObject
{
    /**
     * constructor
     **/
    public function __construct()
    {
        $this->initVar('tplset_id', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('tplset_name', Dtype::TYPE_OTHER, null, false);
        $this->initVar('tplset_desc', Dtype::TYPE_TEXT_BOX, null, false, 255);
        $this->initVar('tplset_credits', Dtype::TYPE_TEXT_AREA, null, false);
        $this->initVar('tplset_created', Dtype::TYPE_INTEGER, 0, false);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('tplset_id', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function tplset_id($format = '')
    {
        return $this->getVar('tplset_id', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function tplset_name($format = '')
    {
        return $this->getVar('tplset_name', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function tplset_desc($format = '')
    {
        return $this->getVar('tplset_desc', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function tplset_credits($format = '')
    {
        return $this->getVar('tplset_credits', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function tplset_created($format = '')
    {
        return $this->getVar('tplset_created', $format);
    }
}

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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;

/**
 * A Config-Option
 *
 * @author    Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 */
class XoopsConfigOption extends XoopsObject
{
    /**
     * Constructor
     */
    function __construct()
    {
        $this->initVar('confop_id', XOBJ_DTYPE_INT, null);
        $this->initVar('confop_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('confop_value', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('conf_id', XOBJ_DTYPE_INT, 0);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('confop_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function confop_id($format = '')
    {
        return $this->getVar('confop_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function confop_name($format = '')
    {
        return $this->getVar('confop_name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function confop_value($format = '')
    {
        return $this->getVar('confop_value', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_id($format = '')
    {
        return $this->getVar('conf_id', $format);
    }

}

/**
 * XOOPS configuration option handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS configuration option class objects.
 *
 * @copyright 2000-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @author    Kazumi Ono <onokazu@xoops.org>
 */
class XoopsConfigOptionHandler extends XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param Connection|null $db {@link Connection}
     */
    public function __construct(Connection $db = null)
    {
        parent::__construct($db, 'configoption', 'XoopsConfigOption', 'confop_id', 'confop_name');
    }
}

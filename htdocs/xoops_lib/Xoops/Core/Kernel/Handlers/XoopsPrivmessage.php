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
 * Private Messages
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 *
 * @package kernel
 **/
class XoopsPrivmessage extends XoopsObject
{
    /**
     * constructor
     **/
    public function __construct()
    {
        $this->initVar('msg_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('msg_image', XOBJ_DTYPE_OTHER, null, false, 100);
        $this->initVar('subject', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('from_userid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('to_userid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('msg_time', XOBJ_DTYPE_OTHER, time(), false);
        $this->initVar('msg_text', XOBJ_DTYPE_TXTAREA, null, true);
        $this->initVar('read_msg', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('msg_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function msg_id($format = '')
    {
        return $this->getVar('msg_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function msg_image($format = '')
    {
        return $this->getVar('msg_image', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function subject($format = '')
    {
        return $this->getVar('subject', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function from_userid($format = '')
    {
        return $this->getVar('from_userid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function to_userid($format = '')
    {
        return $this->getVar('to_userid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function msg_time($format = '')
    {
        return $this->getVar('msg_time', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function msg_text($format = '')
    {
        return $this->getVar('msg_text', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function read_msg($format = '')
    {
        return $this->getVar('read_msg', $format);
    }

}

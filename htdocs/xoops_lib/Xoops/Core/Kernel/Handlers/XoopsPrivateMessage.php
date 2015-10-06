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
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package   kernel
 * @since     2.0.0
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 */

namespace Xoops\Core\Kernel\Handlers;

use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;

/**
 * Private Messages
 *
 * @category  Xoops\Core\Kernel\Handlers\XoopsPrivateMessage
 * @package   Xoops\Core\Kernel
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsPrivateMessage extends XoopsObject
{
    /**
     * constructor
     **/
    public function __construct()
    {
        $this->initVar('msg_id', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('msg_image', Dtype::TYPE_OTHER, null, false, 100);
        $this->initVar('subject', Dtype::TYPE_TEXT_BOX, null, true, 255);
        $this->initVar('from_userid', Dtype::TYPE_INTEGER, null, true);
        $this->initVar('to_userid', Dtype::TYPE_INTEGER, null, true);
        $this->initVar('msg_time', Dtype::TYPE_OTHER, time(), false);
        $this->initVar('msg_text', Dtype::TYPE_TEXT_AREA, null, true);
        $this->initVar('read_msg', Dtype::TYPE_INTEGER, 0, false);
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
        return $this->getVar('msg_id', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function msg_id($format = '')
    {
        return $this->getVar('msg_id', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function msg_image($format = '')
    {
        return $this->getVar('msg_image', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function subject($format = '')
    {
        return $this->getVar('subject', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function from_userid($format = '')
    {
        return $this->getVar('from_userid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function to_userid($format = '')
    {
        return $this->getVar('to_userid', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function msg_time($format = '')
    {
        return $this->getVar('msg_time', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function msg_text($format = '')
    {
        return $this->getVar('msg_text', $format);
    }

    /**
     * getter
     *
     * @param string $format Dtype::FORMAT_xxxx constant
     *
     * @return mixed
     */
    public function read_msg($format = '')
    {
        return $this->getVar('read_msg', $format);
    }
}

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
 * A Template File
 *
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 * @package   kernel
 */
class XoopsTplFile extends XoopsObject
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('tpl_id', Dtype::TYPE_INTEGER, null, false);
        $this->initVar('tpl_refid', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('tpl_tplset', Dtype::TYPE_OTHER, null, false);
        $this->initVar('tpl_file', Dtype::TYPE_TEXT_BOX, null, true, 100);
        $this->initVar('tpl_desc', Dtype::TYPE_TEXT_BOX, null, false, 100);
        $this->initVar('tpl_lastmodified', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('tpl_lastimported', Dtype::TYPE_INTEGER, 0, false);
        $this->initVar('tpl_module', Dtype::TYPE_OTHER, null, false);
        $this->initVar('tpl_type', Dtype::TYPE_OTHER, null, false);
        $this->initVar('tpl_source', Dtype::TYPE_SOURCE, null, false);
    }

    /**
     * id
     *
     * @param string $format
     *
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('tpl_id', $format);
    }

    /**
     * tpl_id
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_id($format = '')
    {
        return $this->getVar('tpl_id', $format);
    }

    /**
     * tpl_refid
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_refid($format = '')
    {
        return $this->getVar('tpl_refid', $format);
    }

    /**
     * tpl_tplset
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_tplset($format = '')
    {
        return $this->getVar('tpl_tplset', $format);
    }

    /**
     * tpl_file
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_file($format = '')
    {
        return $this->getVar('tpl_file', $format);
    }

    /**
     * tpl_desc
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_desc($format = '')
    {
        return $this->getVar('tpl_desc', $format);
    }

    /**
     * tpl_lastmodified
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_lastmodified($format = '')
    {
        return $this->getVar('tpl_lastmodified', $format);
    }

    /**
     * tpl_lastimported
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_lastimported($format = '')
    {
        return $this->getVar('tpl_lastimported', $format);
    }

    /**
     * tpl_module
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_module($format = '')
    {
        return $this->getVar('tpl_module', $format);
    }

    /**
     * tpl_type
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_type($format = '')
    {
        return $this->getVar('tpl_type', $format);
    }

    /**
     * tpl_source
     *
     * @param string $format
     *
     * @return mixed
     */
    public function tpl_source($format = '')
    {
        return $this->getVar('tpl_source', $format);
    }


    /**
     * getSource
     *
     * @return string
     */
    public function getSource()
    {
        return $this->getVar('tpl_source');
    }

    /**
     * getLastModified
     *
     * @return int
     */
    public function getLastModified()
    {
        return $this->getVar('tpl_lastmodified');
    }
}

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

/**#@+
 * deprecated
 * Config type
 */
define('XOOPS_CONF', 1);
define('XOOPS_CONF_USER', 2);
define('XOOPS_CONF_METAFOOTER', 3);
define('XOOPS_CONF_CENSOR', 4);
define('XOOPS_CONF_SEARCH', 5);
define('XOOPS_CONF_MAILER', 6);
define('XOOPS_CONF_AUTH', 7);
/**#@-*/

/**
 * @author        Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 */
class XoopsConfigItem extends XoopsObject
{

    /**
     * Config options
     *
     * @var    array
     * @access    private
     */
    private $_confOptions = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initVar('conf_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('conf_modid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('conf_catid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('conf_name', XOBJ_DTYPE_OTHER);
        $this->initVar('conf_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('conf_value', XOBJ_DTYPE_TXTAREA);
        $this->initVar('conf_desc', XOBJ_DTYPE_OTHER);
        $this->initVar('conf_formtype', XOBJ_DTYPE_OTHER);
        $this->initVar('conf_valuetype', XOBJ_DTYPE_OTHER);
        $this->initVar('conf_order', XOBJ_DTYPE_INT);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function id($format = 'n')
    {
        return $this->getVar('conf_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_id($format = '')
    {
        return $this->getVar('conf_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_modid($format = '')
    {
        return $this->getVar('conf_modid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_catid($format = '')
    {
        return $this->getVar('conf_catid', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_name($format = '')
    {
        return $this->getVar('conf_name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_title($format = '')
    {
        return $this->getVar('conf_title', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_value($format = '')
    {
        return $this->getVar('conf_value', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_desc($format = '')
    {
        return $this->getVar('conf_desc', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_formtype($format = '')
    {
        return $this->getVar('conf_formtype', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_valuetype($format = '')
    {
        return $this->getVar('conf_valuetype', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function conf_order($format = '')
    {
        return $this->getVar('conf_order', $format);
    }

    /**
     * Get a config value in a format ready for output
     *
     * @return    string
     */
    public function getConfValueForOutput()
    {
        switch ($this->getVar('conf_valuetype')) {
        case 'int':
            return (int)($this->getVar('conf_value', 'n'));
            break;
        case 'array':
            $value = @unserialize($this->getVar('conf_value', 'n'));
            return $value ? $value : array();
        case 'float':
            $value = $this->getVar('conf_value', 'n');
            return (float)$value;
            break;
        case 'textarea':
            return $this->getVar('conf_value');
        default:
            return $this->getVar('conf_value', 'n');
            break;
        }
    }

    /**
     * Set a config value
     *
     * @param    mixed   &$value Value
     * @param    bool    $force_slash
     */
    public function setConfValueForInput(&$value, $force_slash = false)
    {
        switch ($this->getVar('conf_valuetype')) {
        case 'array':
            if (!is_array($value)) {
                $value = explode('|', trim($value));
            }
            $this->setVar('conf_value', serialize($value), $force_slash);
            break;
        case 'text':
            $this->setVar('conf_value', trim($value), $force_slash);
            break;
        default:
            $this->setVar('conf_value', $value, $force_slash);
            break;
        }
    }

    /**
     * Assign one or more {@link XoopsConfigOption}s
     *
     * @param    mixed   $option either a {@link XoopsConfigOption} object or an array of them
     */
    public function setConfOptions($option)
    {
        if (is_array($option)) {
            $count = count($option);
            for ($i = 0; $i < $count; ++$i) {
                $this->setConfOptions($option[$i]);
            }
        } else {
            if (is_object($option)) {
                $this->_confOptions[] = $option;
            }
        }
    }

    /**
     * Get the {@link XoopsConfigOption}s of this Config
     *
     * @return    array   array of {@link XoopsConfigOption}
     */
    public function getConfOptions()
    {
        return $this->_confOptions;
    }

    /**
     * Clear options from this item
     *
     * @return void
     **/
    public function clearConfOptions()
    {
        $this->_confOptions = array();
    }
}

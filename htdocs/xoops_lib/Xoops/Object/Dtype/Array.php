<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class Xoops_Object_Dtype_Array extends Xoops_Object_Dtype_Abstract
{
    /**
     * @param XoopsObject $obj
     * @param string      $key
     * @param string      $format
     *
     * @return array|mixed
     */
    public function getVar(XoopsObject $obj, $key, $format)
    {
        $value = $obj->vars[$key]['value'];
        switch (strtolower($format)) {
            case 'n':
            case 'none':
                return $value;
            default:
                if (!is_array($value)) {
                    if ($value != '') {
                        $value = unserialize($value);
                    }
                    $value = is_array($value) ? $value : array();
                }
                return $value;
        }
    }

    /**
     * @param XoopsObject $obj
     * @param string      $key
     * @param bool        $quote
     *
     * @return string
     */
    public function cleanVar(XoopsObject $obj, $key, $quote = true)
    {
        $value = $obj->vars[$key]['value'];
        $value = (array)$value;
        if (!$obj->vars[$key]['not_gpc']) {
            $value = array_map(array(&$this->ts, "stripSlashesGPC"), $value);
        }
        foreach (array_keys($value) as $key) {
            if ($quote) {
                $value[$key] = str_replace('\\"', '"', addslashes($value[$key]));
            }
        }
        // TODO: Not encoding safe, should try base64_encode -- phppp
        $value = "'" . serialize($value) . "'";
        return $value;
    }
}
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

class Xoops_Object_Dtype_Url extends Xoops_Object_Dtype_Abstract
{
    /**
     * @param XoopsObject $obj
     * @param string      $key
     * @param bool        $quote
     *
     * @return string
     */
    function cleanVar(XoopsObject $obj, $key, $quote = true)
    {
        $value = trim($obj->vars[$key]['value']);
        if ($obj->vars[$key]['required'] && $value == '') {
            $obj->setErrors(sprintf(XoopsLocale::F_IS_REQUIRED, $key));
            return $value;
        }
        if ($value != '' && !preg_match("/^http[s]*:\/\//i", $value)) {
            $value = 'http://' . $value;
        }
        if (!$obj->vars[$key]['not_gpc']) {
            $value = $this->ts->stripSlashesGPC($value);
        }
        if ($quote) {
            $value = str_replace('\\"', '"', $this->db->quote($value));
        }
        return $value;
    }
}
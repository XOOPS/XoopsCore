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

class Xoops_Object_Dtype_Textbox extends Xoops_Object_Dtype_Abstract
{
    /**
     * @param XoopsObject $obj
     * @param string      $key
     * @param string      $format
     *
     * @return string
     */
    public function getVar(XoopsObject $obj, $key, $format)
    {
        $value = $obj->vars[$key]['value'];
        switch (strtolower($format)) {
            case 's':
            case 'show':
            case 'e':
            case 'edit':
                return $this->ts->htmlSpecialChars($value);
            case 'p':
            case 'preview':
            case 'f':
            case 'formpreview':
                return $this->ts->htmlSpecialChars($this->ts->stripSlashesGPC($value));
            case 'n':
            case 'none':
            default:
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
        if ($obj->vars[$key]['required'] && $value != '0' && $value == '') {
            $obj->setErrors(sprintf(XoopsLocale::F_IS_REQUIRED, $key));
            return $value;
        }
        if (isset($obj->vars[$key]['maxlength']) && strlen($value) > intval($obj->vars[$key]['maxlength'])) {
            $obj->setErrors(sprintf(XoopsLocale::F_MUST_BE_SHORTER_THAN, $key, intval($obj->vars[$key]['maxlength'])));
            return $value;
        }
        if (!$obj->vars[$key]['not_gpc']) {
            $value = $this->ts->stripSlashesGPC($this->ts->censorString($value));
        } else {
            $value = $this->ts->censorString($value);
        }
        if ($quote) {
            $value = str_replace('\\"', '"', $this->db->quote($value));
        }
        return $value;
    }
}

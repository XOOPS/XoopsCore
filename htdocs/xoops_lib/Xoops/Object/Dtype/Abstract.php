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

abstract class Xoops_Object_Dtype_Abstract
{
    /**
     * @var XoopsConnection
     */
    protected $db;

    /**
     * @var MytextSanitizer
     */
    protected $ts;

    /**
     * Sets database and sanitizer for easy access
     */
    public function init()
    {
        global $xoopsDB;

        $this->db = $xoopsDB;
        $this->ts = MyTextSanitizer::getInstance();
    }

    /**
     * @param XoopsObject $obj
     * @param string      $key
     * @param bool        $quote
     *
     * @return mixed
     */
    public function cleanVar(XoopsObject $obj, $key, $quote = true)
    {
        $value = $obj->vars[$key]['value'];
        if ($quote) {
            $value = str_replace('\\"', '"', $this->db->quote($value));
        }
        return $value;
    }

    /**
     * @param XoopsObject       $obj
     * @param string            $key
     * @param string            $format
     *
     * @return mixed
     */
    public function getVar(XoopsObject $obj, $key, $format)
    {
        $value = $obj->vars[$key]['value'];
        if ($obj->vars[$key]['options'] != '' && $value != '') {
            switch (strtolower($format)) {
                case 's':
                case 'show':
                    $selected = explode('|', $value);
                    $options = explode('|', $obj->vars[$key]['options']);
                    $i = 1;
                    $ret = array();
                    foreach ($options as $op) {
                        if (in_array($i, $selected)) {
                            $ret[] = $op;
                        }
                        $i++;
                    }
                    return implode(', ', $ret);
                case 'e':
                case 'edit':
                    return explode('|', $value);
                default:
            }
        }
        return $value;
    }
}
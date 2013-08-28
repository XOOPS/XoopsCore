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

class Xoops_Object_Dtype
{
    /**
     * @param XoopsObject $obj
     * @param             $key
     * @param bool        $quote
     *
     * @return mixed
     */
    public static function cleanVar(XoopsObject $obj, $key, $quote = true)
    {
        return Xoops_Object_Dtype::_loadDtype(Xoops_Object_Dtype::_getDtypeName($obj, $key))->cleanVar($obj, $key, $quote);
    }

    /**
     * @param XoopsObject       $obj
     * @param string            $key
     * @param string            $format
     *
     * @return mixed
     */
    public static function getVar(XoopsObject $obj, $key, $format)
    {
        return Xoops_Object_Dtype::_loadDtype(Xoops_Object_Dtype::_getDtypeName($obj, $key))
                ->getVar($obj, $key, $format);
    }

    /**
     * @param string $name
     *
     * @return null|Xoops_Object_Dtype_Abstract
     */
    private static function _loadDtype($name)
    {
        static $dtypes;

        $name = ucfirst(strtolower($name));
        $dtype = null;
        if (!isset($dtypes[$name])) {
            if (XoopsLoad::fileExists($file = dirname(__FILE__) . "/Dtype/{$name}.php")) {
                include_once $file;
                $className = "Xoops_Object_Dtype_" . ucfirst($name);
                $dtype = new $className();
            }

            if (!$dtype instanceof Xoops_Object_Dtype_Abstract) {
                trigger_error("Dtype '{$name}' not found", E_USER_WARNING);
                $name = 'other';
                $dtype = new Xoops_Object_Dtype_Other();
            }
            $dtype->init();
            $dtypes[$name] = $dtype;
        }

        return $dtypes[$name];
    }

    /**
     * @param XoopsObject $obj
     * @param             $key
     *
     * @return string
     */
    private static function _getDtypeName(XoopsObject $obj, $key)
    {
        $name = $obj->vars[$key]['data_type'];
        $lNames = Xoops_Object_Dtype::_getLegacyNames();
        if (isset($lNames[$name])) {
            return $lNames[$name];
        }
        return $name;
    }

    /**
     * Support for legacy objects
     *
     * @return array
     */
    private static function _getLegacyNames()
    {
        return array(
            1 => 'textbox', 2 => 'textarea', 3 => 'int', 4 => 'url', 5 => 'email', 6 => 'array', 7 => 'other',
            8 => 'source', 9 => 'stime', 10 => 'mtime', 11 => 'ltime', 13 => 'float', 14 => 'decimal', 15 => 'enum'
        );
    }
}
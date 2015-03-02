<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Kernel;

use Xoops\Core\Kernel\Dtype\DtypeAbstract;

/**
 * Dtype
 *
 * @category  Xoops\Core\Kernel\Dtype
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class Dtype
{
    /**
     * cleanVar
     *
     * @param XoopsObject $obj   object
     * @param mixed       $key   key
     * @param bool        $quote quote result
     *
     * @return mixed
     */
    public static function cleanVar(XoopsObject $obj, $key, $quote = true)
    {
        return Dtype::loadDtype(Dtype::getDtypeName($obj, $key))->cleanVar($obj, $key, $quote);
    }

    /**
     * getVar
     *
     * @param XoopsObject $obj    object
     * @param string      $key    key
     * @param string      $format format
     *
     * @return mixed
     */
    public static function getVar(XoopsObject $obj, $key, $format)
    {
        return Dtype::loadDtype(Dtype::getDtypeName($obj, $key))
                ->getVar($obj, $key, $format);
    }

    /**
     * loadDtype
     *
     * @param string $name dtype name to load
     *
     * @return null|DtypeAbstract
     */
    private static function loadDtype($name)
    {
        static $dtypes;

        $name = ucfirst(strtolower($name));
        $dtype = null;
        if (!isset($dtypes[$name])) {
            $className = 'Xoops\Core\Kernel\Dtype\Dtype' . ucfirst($name);
            @$dtype = new $className();
            if (!$dtype instanceof DtypeAbstract) {
                trigger_error("Dtype '{$name}' not found", E_USER_WARNING);
                $name = 'other';
                $dtype = new Xoops\Core\Kernel\Dtype\DtypeOther();
            }
            $dtype->init();
            $dtypes[$name] = $dtype;
        }

        return $dtypes[$name];
    }

    /**
     * getDtypeName
     *
     * @param XoopsObject $obj object
     * @param mixed       $key key
     *
     * @return string
     */
    private static function getDtypeName(XoopsObject $obj, $key)
    {
        $name = $obj->vars[$key]['data_type'];
        $lNames = Dtype::getLegacyNames();
        if (isset($lNames[$name])) {
            return $lNames[$name];
        }
        return $name;
    }

    /**
     * Support for legacy objects
     *
     * @return string[]
     */
    private static function getLegacyNames()
    {
        return array(
            1 => 'textbox', 2 => 'textarea', 3 => 'int', 4 => 'url', 5 => 'email', 6 => 'array', 7 => 'other',
            8 => 'source', 9 => 'stime', 10 => 'mtime', 11 => 'ltime', 13 => 'float', 14 => 'decimal', 15 => 'enum'
        );
    }
}

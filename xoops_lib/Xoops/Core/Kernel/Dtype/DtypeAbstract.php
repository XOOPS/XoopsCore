<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Kernel\Dtype;

use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Text\Sanitizer;

/**
 * DtypeAbstract
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeAbstract
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class DtypeAbstract
{
    /**
     * @var \Xoops\Core\Database\Connection
     */
    protected $db;

    /**
     * @var Sanitizer
     */
    protected $ts;

    /**
     * Sets database and sanitizer for easy access
     */
    public function __construct()
    {
        //$this->db = \Xoops::getInstance()->db();
        $this->ts = Sanitizer::getInstance();
    }

    /**
     * cleanVar prepare variable for persistence
     *
     * @param XoopsObject $obj object containing variable
     * @param string      $key name of variable
     *
     * @return mixed
     */
    public function cleanVar(XoopsObject $obj, $key)
    {
        $value = $obj->vars[$key]['value'];
        return $value;
    }

    /**
     * getVar get variable prepared according to format
     *
     * @param XoopsObject $obj    object containing variable
     * @param string      $key    name of variable
     * @param string      $format Dtype::FORMAT_* constant indicating desired formatting
     *
     * @return mixed
    */
    public function getVar(XoopsObject $obj, $key, $format)
    {
        $value = $obj->vars[$key]['value'];
        if ($obj->vars[$key]['options'] != '' && $value != '') {
            switch (strtolower($format)) {
                case 's':
                case Dtype::FORMAT_SHOW:
                    $selected = explode('|', $value);
                    $options = explode('|', $obj->vars[$key]['options']);
                    $i = 1;
                    $ret = array();
                    foreach ($options as $op) {
                        if (in_array($i, $selected)) {
                            $ret[] = $op;
                        }
                        ++$i;
                    }
                    return implode(', ', $ret);
                case 'e':
                case Dtype::FORMAT_EDIT:
                    return explode('|', $value);
                default:
            }
        }
        return $value;
    }
}

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

use \DateTime;
use Xoops\Core\Kernel\Dtype;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Locale\Time;

/**
 * DtypeDateTime
 *
 * Data is stored as integer unix timestamp, returned as \DateTime object
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeOldTime
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DtypeDateTime extends DtypeAbstract
{
    /**
     * getVar get variable prepared according to format
     *
     * @param XoopsObject $obj    object containing variable
     * @param string      $key    name of variable
     * @param string      $format Dtype::FORMAT_* constant indicating desired formatting
     *
     * @return int|DateTime
     */
    public function getVar(XoopsObject $obj, $key, $format)
    {
        $storedValue = $obj->vars[$key]['value'];
        switch (strtolower($format)) {
            case Dtype::FORMAT_NONE:
            case 'n':
                $value = $storedValue;
                break;
            default:
                $value = Time::cleanTime($storedValue);
                break;
        }
        return $value;
    }

    /**
     * cleanVar prepare variable for persistence
     *
     * @param XoopsObject $obj object containing variable
     * @param string      $key name of variable
     *
     * @return int
     */
    public function cleanVar(XoopsObject $obj, $key)
    {
        $value = $obj->vars[$key]['value'];
        if ($value instanceof DateTime) {
            $cleanValue = $value->getTimestamp();
        } elseif (is_string($value)) {
            $cleanValue = strtotime($value);
        } else {
            $cleanValue = (int) $value;
        }
        return $cleanValue;
    }
}

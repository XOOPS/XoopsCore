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

use Xoops\Core\Kernel\Dtype\DtypeAbstract;
use Xoops\Core\Kernel\XoopsObject;

/**
 * DtypeDecimal
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeDecimal
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class DtypeDecimal extends DtypeAbstract
{
    /**
     * @param XoopsObject $obj
     * @param string      $key
     * @param bool        $quote
     *
     * @return float|mixed
     */
    public function cleanVar(XoopsObject $obj, $key, $quote = true)
    {
        $value = $obj->vars[$key]['value'];
        $value = doubleval($value);
        return $value;
    }
}

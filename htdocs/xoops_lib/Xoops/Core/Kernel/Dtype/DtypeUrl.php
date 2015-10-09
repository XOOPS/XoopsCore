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

/**
 * DtypeUrl
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeUrl
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DtypeUrl extends DtypeAbstract
{
    /**
     * cleanVar prepare variable for persistence
     *
     * @param XoopsObject $obj object containing variable
     * @param string      $key name of variable
     *
     * @return string
     */
    public function cleanVar(XoopsObject $obj, $key)
    {
        $value = trim($obj->vars[$key]['value']);
        if ($obj->vars[$key]['required'] && $value == '') {
            $obj->setErrors(sprintf(\XoopsLocale::F_IS_REQUIRED, $key));
            return $value;
        }
        if ($value != '' && !preg_match("/^http[s]*:\/\//i", $value)) {
            $value = 'http://' . $value;
        }
        return $value;
    }
}

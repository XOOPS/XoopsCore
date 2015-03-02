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
 * DtypeUrl
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeUrl
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class DtypeUrl extends DtypeAbstract
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
            $obj->setErrors(sprintf(\XoopsLocale::F_IS_REQUIRED, $key));
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

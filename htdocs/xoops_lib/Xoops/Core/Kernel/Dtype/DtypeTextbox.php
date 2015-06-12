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
 * DtypeTextbox
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeTextbox
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class DtypeTextbox extends DtypeAbstract
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
            $obj->setErrors(sprintf(\XoopsLocale::F_IS_REQUIRED, $key));
            return $value;
        }
        if (isset($obj->vars[$key]['maxlength']) && mb_strlen($value) > (int)($obj->vars[$key]['maxlength'])) {
            $obj->setErrors(sprintf(\XoopsLocale::F_MUST_BE_SHORTER_THAN, $key, (int)($obj->vars[$key]['maxlength'])));
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

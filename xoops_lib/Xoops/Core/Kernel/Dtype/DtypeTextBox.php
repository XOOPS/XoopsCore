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
 * DtypeTextBox
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeTextBox
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class DtypeTextBox extends DtypeAbstract
{
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
        switch (strtolower($format)) {
            case 's':
            case Dtype::FORMAT_SHOW:
            case 'e':
            case Dtype::FORMAT_EDIT:
                return $this->ts->htmlSpecialChars($value);
            case 'p':
            case Dtype::FORMAT_PREVIEW:
            case 'f':
            case Dtype::FORMAT_FORM_PREVIEW:
                return $this->ts->htmlSpecialChars($value);
            case 'n':
            case Dtype::FORMAT_NONE:
            default:
                return $value;
        }
    }

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
        $value = $obj->vars[$key]['value'];
        if ($obj->vars[$key]['required'] && $value != '0' && $value == '') {
            $obj->setErrors(sprintf(\XoopsLocale::F_IS_REQUIRED, $key));
            return $value;
        }
        if (isset($obj->vars[$key]['maxlength']) && mb_strlen($value) > (int)($obj->vars[$key]['maxlength'])) {
            $obj->setErrors(sprintf(\XoopsLocale::F_MUST_BE_SHORTER_THAN, $key, (int)($obj->vars[$key]['maxlength'])));
            return $value;
        }

        $value = $this->ts->censorString($value);

        return $value;
    }
}

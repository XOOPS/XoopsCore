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
 * DtypeTextArea
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeTextArea
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DtypeTextArea extends DtypeAbstract
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
                $html = !empty($obj->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($obj->vars['doxcode']['value']) || $obj->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($obj->vars['dosmiley']['value']) || $obj->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($obj->vars['doimage']['value']) || $obj->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($obj->vars['dobr']['value']) || $obj->vars['dobr']['value'] == 1) ? 1 : 0;
                return $this->ts->displayTarea($value, $html, $smiley, $xcode, $image, $br);

            case 'e':
            case Dtype::FORMAT_EDIT:
                return htmlspecialchars($value, ENT_QUOTES);
            case 'p':
            case Dtype::FORMAT_PREVIEW:
                $html = !empty($obj->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($obj->vars['doxcode']['value']) || $obj->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($obj->vars['dosmiley']['value']) || $obj->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($obj->vars['doimage']['value']) || $obj->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($obj->vars['dobr']['value']) || $obj->vars['dobr']['value'] == 1) ? 1 : 0;
                return $this->ts->previewTarea($value, $html, $smiley, $xcode, $image, $br);
            case 'f':
            case Dtype::FORMAT_FORM_PREVIEW:
                return htmlspecialchars($value, ENT_QUOTES);
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

        $value = $this->ts->censorString($value);

        return $value;
    }
}

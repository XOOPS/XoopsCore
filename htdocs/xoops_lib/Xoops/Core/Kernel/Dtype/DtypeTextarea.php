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
 * DtypeTextarea
 *
 * @category  Xoops\Core\Kernel\Dtype\DtypeTextarea
 * @package   Xoops\Core\Kernel
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class DtypeTextarea extends DtypeAbstract
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
                $html = !empty($obj->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($obj->vars['doxcode']['value']) || $obj->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($obj->vars['dosmiley']['value']) || $obj->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($obj->vars['doimage']['value']) || $obj->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($obj->vars['dobr']['value']) || $obj->vars['dobr']['value'] == 1) ? 1 : 0;
                return $this->ts->displayTarea($value, $html, $smiley, $xcode, $image, $br);

            case 'e':
            case 'edit':
                return htmlspecialchars($value, ENT_QUOTES);
            case 'p':
            case 'preview':
                $html = !empty($obj->vars['dohtml']['value']) ? 1 : 0;
                $xcode = (!isset($obj->vars['doxcode']['value']) || $obj->vars['doxcode']['value'] == 1) ? 1 : 0;
                $smiley = (!isset($obj->vars['dosmiley']['value']) || $obj->vars['dosmiley']['value'] == 1) ? 1 : 0;
                $image = (!isset($obj->vars['doimage']['value']) || $obj->vars['doimage']['value'] == 1) ? 1 : 0;
                $br = (!isset($obj->vars['dobr']['value']) || $obj->vars['dobr']['value'] == 1) ? 1 : 0;
                return $this->ts->previewTarea($value, $html, $smiley, $xcode, $image, $br);
            case 'f':
            case 'formpreview':
                return htmlspecialchars($this->ts->stripSlashesGPC($value), ENT_QUOTES);
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

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS form element of colorpicker
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Zoullou <webmaster@zoullou.org>
 * @author          John Neill <catzwolf@xoops.org>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsFormColorPicker extends XoopsFormText
{
    /**
     * @param mixed $caption
     * @param mixed $name
     * @param string $value
     */
    public function XoopsFormColorPicker($caption, $name, $value = '#FFFFFF')
    {
        parent::__construct($caption, $name, 2, 7, $value, '');
    }

    /**
     * @return string
     */
    public function render()
    {
        $xoops = Xoops::getInstance();
        if ($xoops->theme()) {
            $xoops->theme()->addScript('include/color-picker.js');
        } else {
            echo '<script type="text/javascript" src="' . XOOPS_URL . '/include/color-picker.js"></script>';
        }
        $this->setExtra(' style="background-color:' . $this->getValue() . ';"');
        return parent::render() . "<button class='btn' type='button' onclick=\"return TCP.popup('" . XOOPS_URL . "/include/',document.getElementById('" . $this->getName() . "'));\"> ... </button>";

    }

    /**
     * Returns custom validation Javascript
     *
     * @return string Element validation Javascript
     */
    public function renderValidationJS()
    {
        $eltname = $this->getName();
        $eltcaption = $this->getCaption();
        $eltmsg = empty($eltcaption) ? sprintf(XoopsLocale::F_ENTER, $eltname) : sprintf(XoopsLocale::F_ENTER, $eltcaption);

        return "if ( !(new RegExp(\"^#[0-9a-fA-F]{6}\",\"i\").test(myform.{$eltname}.value)) ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
    }
}
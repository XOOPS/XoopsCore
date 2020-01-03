<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Form;

/**
 * ColorPicker - colorpicker form element
 *
 * @category  Xoops\Form\ColorPicker
 * @package   Xoops\Form
 * @author    Zoullou <webmaster@zoullou.org>
 * @author    John Neill <catzwolf@xoops.org>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      https://xoops.org
 */
class ColorPicker extends Text
{
    /**
     * __construct
     *
     * @param string|array $caption field caption or array of all attributes
     * @param string       $name    field name
     * @param string       $value   field value
     */
    public function __construct($caption, $name = null, $value = '#FFFFFF')
    {
        if (is_array($caption)) {
            parent::__construct($caption);
            $value = $this->get('value','');
            if (empty($value)) {
                $this->set('value', '#FFFFFF');
            }
            $this->setIfNotSet('size', 10);
            $this->setIfNotSet('maxlength', 16);
        } else {
            parent::__construct([]);
            $this->set('caption', $caption);
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('size', 10);
            $this->set('maxlength', 16);
            $this->set('value', $value);
        }
        $this->setIfNotSet('type', 'text');
    }

    /**
     * defaultRender
     *
     * @return string rendered form element
     */
    public function defaultRender()
    {
        $xoops = \Xoops::getInstance();
        if ($xoops->theme()) {
            $xoops->theme()->addScript('include/color-picker.js');
        } else {
            echo '<script type="text/javascript" src="' . $xoops->url('/include/color-picker.js') . '"></script>';
        }
        $temp = $this->get('value', '');
        if (!empty($temp)) {
            $this->set('style', 'background-color:' . $temp . ';');
        }
        $ret = '<div>';
        $attributes = $this->renderAttributeString();
        $ret .= '<input ' . $attributes . ' ' . $this->getExtra() .' >';
        $ret .= '<span>';
        $ret .= '<button type="button" ';
        $ret .= 'data-toggle="tooltip" data-placement="left" title="' . \XoopsLocale::A_SELECT . '" ';
        $ret .= 'onclick="return TCP.popup(\'';
        $ret .= $xoops->url('/include/') . '\',document.getElementById(\'' . $this->getName() . '\'));">';
        $ret .= '<span>...</span></button>';
        $ret .= '</span></div>';

        return $ret;
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
        $eltmsg = empty($eltcaption)
            ? sprintf(\XoopsLocale::F_ENTER, $eltname)
            : sprintf(\XoopsLocale::F_ENTER, $eltcaption);

        return "if ( !(new RegExp(\"^#[0-9a-fA-F]{6}\",\"i\").test(myform.{$eltname}.value)) )"
            . " { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
    }
}

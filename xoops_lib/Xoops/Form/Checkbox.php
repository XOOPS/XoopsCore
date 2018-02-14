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
 * Checkbox - a checkbox form element
 *
 * @category  Xoops\Form\Checkbox
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Skalpa Keo <skalpa@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Checkbox extends OptionElement
{
    /**
     * pre-selected values in array
     *
     * @var array
     */
    protected $value = array();

    /**
     * Constructor
     *
     * @param string|array $caption Caption or array of all attributes
     *                               Control attributes:
     *                                   :inline true to render with inline style
     * @param string       $name    element name
     * @param mixed        $value   value(s) to be set on display, either one value or an array of them.
     * @param boolean      $inline  true for inline arrangement
     */
    public function __construct($caption, $name = null, $value = null, $inline = true)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->setIfNotSet(':inline', true);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('value', $value);
            $this->set(':inline', $inline);
        }
        $this->set('type', 'checkbox');
    }

    /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $required = $this->has('required');
        $elementOptions = $this->getOptions();
        $elementValue = $this->getValue();
        if (!is_array($elementValue)) {
            $elementValue = (array) $elementValue;
        }
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');

        $elementName = $this->getName();
        $elementId = $elementName;
        if (count($elementOptions) > 1 && substr($elementName, -2, 2) !== '[]') {
            $elementName = $elementName . '[]';
            $this->setName($elementName);
            // If required is set, all checkboxes will be required by the browser,
            // which is not usually useful. We stash the value of required above
            // and unset now. We restore it before return so JS validation will still
            // be triggered. This is only a problem if there is more than one checkbox.
            $this->remove('required');
        }

        $ret = "";
        $inline = (bool) $this->get(':inline', false);
        if ($inline) {
            $ret .= '<div class="input-group">';
        }
        $idCount = 0;
        foreach ($elementOptions as $value => $name) {
            $this->remove('checked');
            if (!empty($elementValue) && in_array($value, $elementValue)) {
                $this->set('checked');
            }
            $this->set('value', $value);
            ++$idCount;
            $this->set('id', $elementId . $idCount);
            if ($inline) {
                $ret .= '<label class="checkbox-inline">';
                $ret .= '<input ' . $this->renderAttributeString() . $extra . ">" . $name . "\n";
                $ret .= "</label>\n";
            } else {
                $ret .= "<div class=\"checkbox\">\n<label>";
                $ret .= '<input ' . $this->renderAttributeString() . $extra . '>' . $name . "\n";
                $ret .= "</label>\n</div>\n";
            }

        }
        if ($required) {
            $this->set('required');
        }
        if ($inline) {
            $ret .= '</div>';
        }
        return $ret;
    }

    /**
     * Render custom javascript validation code
     *
     * @return string
     */
    public function renderValidationJS()
    {
        // render custom validation code if any
        if (!empty($this->customValidationCode)) {
            return implode("\n", $this->customValidationCode);
            // generate validation code if required
        } elseif ($this->isRequired()) {
            $eltname = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = empty($eltcaption)
                ? sprintf(\XoopsLocale::F_ENTER, $eltname)
                : sprintf(\XoopsLocale::F_ENTER, $eltcaption);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
            return "\n"
            . "var hasChecked = false; var checkBox = myform.elements['{$eltname}'];"
            . " if (checkBox.length) {for (var i = 0; i < checkBox.length; i++)"
            . " {if (checkBox[i].checked == true) {hasChecked = true; break;}}}"
            . "else{if (checkBox.checked == true) {hasChecked = true;}}"
            . "if (!hasChecked) {window.alert(\"{$eltmsg}\");if (checkBox.length)"
            . " {checkBox[0].focus();}else{checkBox.focus();}return false;}";
        }
        return '';
    }
}

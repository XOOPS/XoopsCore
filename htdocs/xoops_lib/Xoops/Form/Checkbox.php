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
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author    Skalpa Keo <skalpa@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
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
     * position for this element
     *
     * @var boolean
     */
    private $inline;

    /**
     * Constructor
     *
     * @param string  $caption captiom
     * @param string  $name    element name
     * @param mixed   $value   Either one value as a string or an array of them.
     * @param boolean $inline  true for inline arrangement
     */
    public function __construct($caption, $name, $value = null, $inline = true)
    {
        $this->setAttribute('type', 'checkbox');
        $this->setAttribute('name', $name);
        $this->setCaption($caption);
        if (isset($value)) {
            $this->setValue($value);
        }
        $this->inline = $inline;
    }

    /**
     * Get the position of this group
     *
     * @return string
     */
    public function getInline()
    {
        if ($this->inline == true) {
            return ' inline';
        } else {
            return '';
        }
    }

    /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $required = $this->hasAttribute('required');
        $ele_options = $this->getOptions();
        $ele_value = $this->getValue();
        if (!is_array($ele_value)) {
            $ele_value = (array) $ele_value;
        }
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');

        $ele_name = $this->getName();
        $ele_id = $ele_name;
        if (count($ele_options) > 1 && substr($ele_name, -2, 2) !== '[]') {
            $ele_name = $ele_name . '[]';
            $this->setName($ele_name);
            // If required is set, all checkboxes will be required by the browser,
            // which is not usually useful. We stash the value of required above
            // and unset now. We restore it before return so JS validation will still
            // be triggered. This is only a problem if there is more than one checkbox.
            $this->unsetAttribute('required');
        }

        $ret = "";
        $id_ele = 0;
        foreach ($ele_options as $value => $name) {
            $this->unsetAttribute('checked');
            if (!empty($ele_value) && in_array($value, $ele_value)) {
                $this->setAttribute('checked');
            }
            $this->setAttribute('value', $value);
            ++$id_ele;
            $this->setAttribute('id', $ele_id . $id_ele);
            $ret .= '<label class="checkbox' . $this->getInline() . '">' . NWLINE;
            $ret .= '<input ' . $this->renderAttributeString() . $extra . '>' . NWLINE;
            $ret .= $name . NWLINE;
            $ret .= "</label>" . NWLINE;
        }
        if ($required) {
            $this->setAttribute('required');
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
            return implode(NWLINE, $this->customValidationCode);
            // generate validation code if required
        } elseif ($this->isRequired()) {
            $eltname = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = empty($eltcaption)
                ? sprintf(\XoopsLocale::F_ENTER, $eltname)
                : sprintf(\XoopsLocale::F_ENTER, $eltcaption);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
            return NWLINE . "var hasChecked = false; var checkBox = myform.elements['{$eltname}']; if (checkBox.length) {for (var i = 0; i < checkBox.length; i++) {if (checkBox[i].checked == true) {hasChecked = true; break;}}}else{if (checkBox.checked == true) {hasChecked = true;}}if (!hasChecked) {window.alert(\"{$eltmsg}\");if (checkBox.length) {checkBox[0].focus();}else{checkBox.focus();}return false;}";
        }
        return '';
    }
}

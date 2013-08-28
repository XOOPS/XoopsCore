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
 * XOOPS form element of checkbox compo
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsFormCheckBox extends XoopsFormElement
{
    /**
     * Availlable options
     *
     * @var array
     */
    private $_options = array();

    /**
     * pre-selected values in array
     *
     * @var array
     */
    protected $_value = array();

     /**
     * position for this element
     *
     * @var string
     * @access private
     */
    private $_inline;

    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param mixed $value Either one value as a string or an array of them.
     * @param bool $inline
     */
    public function __construct($caption, $name, $value = null, $inline = true)
    {
        $this->setCaption($caption);
        $this->setName($name);
        if (isset($value)) {
            $this->setValue($value);
        }
        $this->_inline = $inline;
    }

    /**
     * Add an option
     *
     * @param string $value
     * @param string $name
     */
    public function addOption($value, $name = '')
    {
        if ($name != '') {
            $this->_options[$value] = $name;
        } else {
            $this->_options[$value] = $value;
        }
    }

    /**
     * Add multiple Options at once
     *
     * @param array $options Associative array of value->name pairs
     */
    public function addOptionArray($options)
    {
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->addOption($k, $v);
            }
        }
    }

    /**
     * Get an array with all the options
     *
     * @param bool $encode To sanitizer the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name
     * @return array Associative array of value->name pairs
     */
    public function getOptions($encode = false)
    {
        if (!$encode) {
            return $this->_options;
        }
        $value = array();
        foreach ($this->_options as $val => $name) {
            $value[$encode ? htmlspecialchars($val, ENT_QUOTES) : $val] = ($encode > 1)
                ? htmlspecialchars($name, ENT_QUOTES) : $name;
        }
        return $value;
    }

    /**
     * Get the position of this group
     *
     * @return string
     */
    public function getInline()
    {
        if ($this->_inline == true){
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
        $ele_options = $this->getOptions();
        $ele_value = $this->getValue();
        $ele_name = $this->getName();
        $ele_id = $ele_name;
        $ele_title = $this->getTitle();
        $ele_inline = $this->getInline();
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');

        if (count($ele_options) > 1 && substr($ele_name, -2, 2) != '[]') {
            $ele_name = $ele_name . '[]';
            $this->setName($ele_name);
        }

        $ret = "";
        $id_ele = 0;
        foreach ($ele_options as $value => $name) {
            if (count($ele_value) > 0 && in_array($value, $ele_value)) {
                $ele_checked = " checked='checked'";
            } else {
                $ele_checked = '';
            }
            $id_ele++;
            $ret .= "<label class='checkbox" . $ele_inline . "'>" . NWLINE;
            $ret .= "<input type='checkbox' name='" . $ele_name . "' title='" . $ele_title . "' id='" . $ele_id . $id_ele . "' value='" . $value . "'" . $class . $extra . $ele_checked . ">" . NWLINE;
            $ret .= $name . NWLINE;
            $ret .= "</label>" . NWLINE;
        }
        return $ret;
    }

    /**
     * Render custom javascript validation code
     *
     * @seealso XoopsForm::renderValidationJS
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
            $eltmsg = empty($eltcaption) ? sprintf(XoopsLocale::F_ENTER, $eltname) : sprintf(XoopsLocale::F_ENTER, $eltcaption);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
            return NWLINE . "var hasChecked = false; var checkBox = myform.elements['{$eltname}']; if (checkBox.length) {for (var i = 0; i < checkBox.length; i++) {if (checkBox[i].checked == true) {hasChecked = true; break;}}}else{if (checkBox.checked == true) {hasChecked = true;}}if (!hasChecked) {window.alert(\"{$eltmsg}\");if (checkBox.length) {checkBox[0].focus();}else{checkBox.focus();}return false;}";
        }
        return '';
    }
}
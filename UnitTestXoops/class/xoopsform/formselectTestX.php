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
 * Xoops form element of select
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsFormSelect extends XoopsFormElement
{
    /**
     * Options
     *
     * @var array
     */
    private $_options = array();

    /**
     * Allow multiple selections?
     *
     * @var bool
     */
    private $_multiple = false;

    /**
     * Number of rows. "1" makes a dropdown list.
     *
     * @var int
     */
    private $_size;

    /**
     * Pre-selected values
     *
     * @var array
     */
    protected $_value = array();

     /**
     * Optgroup
     *
     * @var array
     */
    private $_optgroup = array();

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param mixed $value Pre-selected value (or array of them).
     * @param int $size Number or rows. "1" makes a drop-down-list
     * @param bool $multiple Allow multiple selections?
     */
    public function __construct($caption, $name, $value = null, $size = 1, $multiple = false)
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_multiple = $multiple;
        $this->_size = intval($size);
        if (isset($value)) {
            $this->setValue($value);
        }
    }

    /**
     * Are multiple selections allowed?
     *
     * @return bool
     */
    public function isMultiple()
    {
        return $this->_multiple;
    }

    /**
     * Get the size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Add an option
     *
     * @param string $value "value" attribute
     * @param string $name "name" attribute
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
     * Add multiple options
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
     * Add multiple optgroup
     *
     * @param string $name "name" attribute
     * @param array $optgroup Associative array of value->name pairs
     */
    public function addOptgroup($name = '', $optgroup)
    {
        $this->_optgroup[$name] = $optgroup;
    }

    /**
     * Get an array with all the options
     *
     * Note: both name and value should be sanitized. However for backward compatibility, only value is sanitized for now.
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
     * Get an array with all the optgroup
     *
     */
    public function getOptgroup()
    {
        return $this->_optgroup;
    }
    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $ele_name = $this->getName();
        $ele_title = $this->getTitle();
        $ele_value = $this->getValue();
        $ele_options = $this->getOptions();
        $ele_optgroup = $this->getOptgroup();
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $ret = '<select' . $class . ' size="' . $this->getSize() . '"' . $extra;
        if ($this->isMultiple() != false) {
            $ret .= ' name="' . $ele_name . '[]" id="' . $ele_name . '" title="' . $ele_title . '" multiple="multiple">' . NWLINE;
        } else {
            $ret .= ' name="' . $ele_name . '" id="' . $ele_name . '" title="' . $ele_title . '">' . NWLINE;
        }

        if (empty($ele_optgroup)) {
            foreach ($ele_options as $value => $name) {
                $ret .= '<option value="' . htmlspecialchars($value, ENT_QUOTES) . '"';
                if (count($ele_value) > 0 && in_array($value, $ele_value)) {
                    $ret .= ' selected="selected"';
                }
                $ret .= '>' . $name . '</option>' . NWLINE;
            }
        } else {
            foreach ($ele_optgroup as $name_optgroup => $value_optgroup) {
                $ret .= '<optgroup label="' . $name_optgroup . '">' . NWLINE;
                foreach ($value_optgroup as $value => $name) {
                    $ret .= '<option value="' . htmlspecialchars($value, ENT_QUOTES) . '"';
                    if (count($ele_value) > 0 && in_array($value, $ele_value)) {
                        $ret .= ' selected="selected"';
                    }
                    $ret .= '>' . $name . '</option>' . NWLINE;
                }
                $ret .= '</optgroup>' . NWLINE;
            }
        }
        $ret .= '</select>' . NWLINE;
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
            return implode("\n", $this->customValidationCode);
            // generate validation code if required
        } elseif ($this->isRequired()) {
            $eltname = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = empty($eltcaption) ? sprintf(XoopsLocale::F_ENTER, $eltname) : sprintf(XoopsLocale::F_ENTER, $eltcaption);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
            return "\nvar hasSelected = false; var selectBox = myform.{$eltname};" . "for (i = 0; i < selectBox.options.length; i++ ) { if (selectBox.options[i].selected == true && selectBox.options[i].value != '') { hasSelected = true; break; } }" . "if (!hasSelected) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
        }
        return '';
    }
}
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
 * Select - a select element
 *
 * @category  Xoops\Form\Select
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class Select extends Element
{
    /**
     * Options
     *
     * @var array
     */
    private $options = array();

    /**
     * Allow multiple selections?
     *
     * @var bool
     */
    //private $multiple = false;

    /**
     * Number of rows. "1" makes a dropdown list.
     *
     * @var int
     */
    //private $size;

    /**
     * Pre-selected values
     *
     * @var array
     */
    protected $value = array();

     /**
     * Optgroup
     *
     * @var array
     */
    private $optgroup = array();

    /**
     * Constructor
     *
     * @param string  $caption  Caption
     * @param string  $name     name" attribute
     * @param mixed   $value    Pre-selected value (or array of them).
     * @param integer $size     Number or rows. "1" makes a drop-down-list
     * @param boolean $multiple Allow multiple selections?
     */
    public function __construct($caption, $name, $value = null, $size = 1, $multiple = false)
    {
        $this->setCaption($caption);
        $this->setAttribute('name', $name);
        $this->setAttribute('size', intval($size));
        if ($multiple) {
            $this->setAttribute('multiple');
        }
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
        return $this->hasAttribute('multiple');
    }

    /**
     * Get the size
     *
     * @return string
     */
    public function getSize()
    {
        return (string) $this->getAttribute('size');
    }

    /**
     * Add an option
     *
     * @param string $value value attribute
     * @param string $name  name attribute
     *
     * @return void
     */
    public function addOption($value, $name = '')
    {
        if ($name != '') {
            $this->options[$value] = $name;
        } else {
            $this->options[$value] = $value;
        }
    }

    /**
     * Add multiple options
     *
     * @param array $options Associative array of value->name pairs
     *
     * @return void
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
     * @param string $name     name attribute
     * @param array  $optgroup Associative array of value->name pairs
     *
     * @return void
     */
    public function addOptgroup($name, $optgroup)
    {
        $this->optgroup[$name] = $optgroup;
    }

    /**
     * Get an array with all the options
     *
     * Note: both name and value should be sanitized. However for backward
     * compatibility, only value is sanitized for now.
     *
     * @param boolean $encode encode special characters, potential values:
     *                        0 - skip
     *                        1 - only for value
     *                        2 - for both value and name
     *
     * @return array Associative array of value->name pairs
     */
    public function getOptions($encode = false)
    {
        if (!$encode) {
            return $this->options;
        }
        $value = array();
        foreach ($this->options as $val => $name) {
            $value[$encode ? htmlspecialchars($val, ENT_QUOTES) : $val] = ($encode > 1)
                ? htmlspecialchars($name, ENT_QUOTES) : $name;
        }
        return $value;
    }

    /**
     * Get an array with all the optgroup
     *
     * @return array
     */
    public function getOptgroup()
    {
        return $this->optgroup;
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
        if (!is_array($ele_value)) {
            $ele_value = (array) $ele_value;
        }
        $ele_options = $this->getOptions();
        $ele_optgroup = $this->getOptgroup();

        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $attributes = $this->renderAttributeString();
        $ret = '<select ' . $attributes . $extra .' >' . NWLINE;

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
            return "\nvar hasSelected = false; var selectBox = myform.{$eltname};"
                . "for (i = 0; i < selectBox.options.length; i++ ) { "
                . "if (selectBox.options[i].selected == true && selectBox.options[i].value != '') "
                . "{ hasSelected = true; break; } }" . "if (!hasSelected) "
                . "{ window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
        }
        return '';
    }
}

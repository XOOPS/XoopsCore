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
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Select extends OptionElement
{
    /**
     * Pre-selected values
     *
     * @var array
     */
    protected $value = array();

    /**
     * Constructor
     *
     * @param string|array $caption  Caption or array of all attributes
     * @param string       $name     name" attribute
     * @param mixed        $value    Pre-selected value (or array of them).
     * @param integer      $size     Number or rows. "1" makes a drop-down-list
     * @param boolean      $multiple Allow multiple selections?
     */
    public function __construct($caption, $name = null, $value = null, $size = 1, $multiple = false)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->setIfNotSet('size', 1);
        } else {
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('value', $value);
            $this->setWithDefaults('size', $size, 1);
            if ($multiple) {
                $this->set('multiple');
            }
        }
    }

    /**
     * Are multiple selections allowed?
     *
     * @return bool
     */
    public function isMultiple()
    {
        return $this->has('multiple');
    }

    /**
     * Get the size
     *
     * @return int
     */
    public function getSize()
    {
        return (int) $this->get('size');
    }

     /**
     * Add multiple optgroup
     *
     * @param string $name     name attribute
     * @param array  $optgroup Associative array of value->name pairs
     *
     * @return void
     */
    public function addOptionGroup($name, $optgroup)
    {
        $this->setArrayItem('option', $name, $optgroup);
    }

    /**
     * render a single option
     *
     * @param string   $optionValue   option element value
     * @param string   $optionDisplay displayed text
     * @param string[] $selected      selected option values
     *
     * @return string
     */
    protected function renderOption($optionValue, $optionDisplay, $selected)
    {
        $rendered = '<option value="' . htmlspecialchars($optionValue, ENT_QUOTES) . '"';
        if (in_array($optionValue, $selected)) {
            $rendered .= ' selected="selected"';
        }
        $rendered .= '>' . $optionDisplay . '</option>' . "\n";

        return $rendered;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $selected = (array) $this->getValue();

        $ele_options = $this->getOptions();

        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $this->themeDecorateElement();
        $attributes = $this->renderAttributeString();
        $rendered = '<select ' . $attributes . $extra .' >' . "\n";

        if (empty($ele_optgroup)) {
            foreach ($ele_options as $value => $display) {
                if (is_array($display)) {
                    $rendered .= '<optgroup label="' . $value . '">' . "\n";
                    foreach ($display as $optvalue => $optdisplay) {
                        $rendered .= $this->renderOption($optvalue, $optdisplay, $selected);
                    }
                } else {
                    $rendered .= $this->renderOption($value, $display, $selected);
                }
            }
        }
        $rendered .= '</select>' . "\n";

        return $rendered;
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

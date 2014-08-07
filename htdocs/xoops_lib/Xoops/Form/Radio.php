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
 * Radio - radio button element
 *
 * @category  Xoops\Form\Radio
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class Radio extends Element
{
    /**
     * Array of Options
     *
     * @var array
     */
    private $options = array();

    /**
     * Pre-selected value
     *
     * @var string
     */
    protected $value = null;

     /**
     * position for this element
     *
     * @var string
     * @access private
     */
    private $inline;

    /**
     * __construct
     *
     * @param string  $caption Caption
     * @param string  $name    name attribute
     * @param string  $value   Pre-selected value
     * @param boolean $inline  true to display inline
     */
    public function __construct($caption, $name, $value = null, $inline = true)
    {
        $this->setCaption($caption);
        $this->setName($name);
        if (isset($value)) {
            $this->setValue($value);
        }
        $this->inline = $inline;
    }

    /**
     * Get the "value" attribute
     *
     * @param boolean $encode True to encode special characters
     *
     * @return string
     */
    public function getValue($encode = false)
    {
        return ($encode && $this->value !== null) ? htmlspecialchars($this->value, ENT_QUOTES) : $this->value;
    }

    /**
     * Add an option
     *
     * @param string $value "value" attribute - This gets submitted as form-data.
     * @param string $name  "name" attribute - This is displayed. If empty, we use the "value" instead.
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
     * Adds multiple options
     *
     * @param array $options Associative array of value->name pairs.
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
     * Get an array with all the options
     *
     * @param integer $encode encode special characters, potential values:
     *                          0 - skip
     *                          1 - only for value;
     *                          2 - for both value and name
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
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $ele_options = $this->getOptions();
        $ele_value = $this->getValue();
        $ele_name = $this->getName();
        $ele_title = $this->getTitle();
        $ele_inline = $this->getInline();
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $required = ($this->isRequired() ? ' required' : '');
        $ret = "";
        static $id_ele = 0;
        foreach ($ele_options as $value => $name) {
            if (isset($ele_value) && $value == $ele_value) {
                $ele_checked = " checked='checked'";
            } else {
                $ele_checked = '';
            }
            $id_ele++;
            $ret .= "<label class='radio" . $ele_inline . "'>" . NWLINE;
            $ret .= "<input type='radio' name='" . $ele_name . "' title='" . $ele_title . "' id='"
                . $ele_name . $id_ele . "' value='" . $value . "'" . $class . $extra . $ele_checked
                . $required . ">" . NWLINE;
            $ret .= $name . NWLINE;
            $ret .= "</label>" . NWLINE;
        }
        return $ret;
    }
}

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
     * inline attribute for this element
     *
     * @var boolean
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
        $this->setAttribute('type', 'radio');
        $this->setAttribute('name', $name);
        $this->setCaption($caption);
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
     * @param string $value         value attribute for option - This gets submitted as form-data.
     * @param string $buttonCaption option button caption - If empty, we use the "value" instead.
     *
     * @return void
     */
    public function addOption($value, $buttonCaption = '')
    {
        if ($buttonCaption != '') {
            $this->options[$value] = $buttonCaption;
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
    public function getOptions($encode = 0)
    {
        if (!$encode) {
            return $this->options;
        }
        $value = array();
        foreach ($this->options as $value => $buttonCaption) {
            $value[$encode ? htmlspecialchars($value, ENT_QUOTES) : $value] = ($encode > 1)
                ? htmlspecialchars($buttonCaption, ENT_QUOTES) : $buttonCaption;
        }
        return $value;
    }

    /**
     * sets the class for inline orientation
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
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $ret = "";
        static $id_ele = 0;
        foreach ($ele_options as $value => $buttonCaption) {
            $this->unsetAttribute('checked');
            if (isset($ele_value) && $value == $ele_value) {
                $this->setAttribute('checked');
            }
            $this->setAttribute('value', $value);
            $id_ele++;
            $this->setAttribute('id', $ele_name . $id_ele);
            $ret .= '<label class="radio' . $this->getInline() . '">' . NWLINE;
            $ret .= '<input ' . $this->renderAttributeString() . $extra . ">" . NWLINE;
            $ret .= $buttonCaption . NWLINE;
            $ret .= "</label>" . NWLINE;
        }
        return $ret;
    }
}

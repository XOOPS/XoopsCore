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

use Xoops\Html\Attributes;

/**
 * OptionElement - Abstract base class for form elements with options (i.e. Select)
 *
 * @category  Xoops\Form\OptionElement
 * @package   Xoops\Form
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class OptionElement extends Element
{
    /**
     * Available options
     *
     * @var array
     */
    protected $options = array();


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
     * Get an array with all the options
     *
     * @param integer $encode encode special characters, potential values:
     *                        0 - skip
     *                        1 - only for value
     *                        2 - for both value and name
     *
     * @return array Associative array of value->name pairs
     */
    public function getOptions($encode = 0)
    {
        if (!$encode) {
            return $this->options;
        }
        $myts = \Xoops\Core\Text\Sanitizer::getInstance();
        $value = array();
        foreach ($this->options as $val => $name) {
            $value[$encode ? $myts->htmlSpecialChars($val) : $val] = ($encode > 1)
                ? $myts->htmlSpecialChars($name) : $name;
        }
        return $value;
    }
}

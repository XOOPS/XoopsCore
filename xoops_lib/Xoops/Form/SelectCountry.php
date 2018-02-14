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
 * SelectCountry - a select field with countries
 *
 * @category  Xoops\Form\SelectCountry
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SelectCountry extends Select
{
    /**
     * Constructor
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $name    "name" attribute
     * @param mixed        $value   Pre-selected value (or array of them).
     *                               Legal are all 2-letter country codes (in capitals).
     * @param int          $size    Number or rows. "1" makes a drop-down-list
     */
    public function __construct($caption, $name = null, $value = null, $size = 1)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->setIfNotSet('size', 1);
        } else {
            parent::__construct($caption, $name, $value, $size);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('value', $value);
            $this->setWithDefaults('size', $size, 1);
        }
        \Xoops\Core\Lists\Country::setOptionsArray($this);
    }
}

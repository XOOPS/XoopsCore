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
 * Raw - raw form element
 *
 * This class has special treatment by xoopsforms, it will render the raw
 * value provided without wrapping in HTML
 *
 * @category  Xoops\Form\Raw
 * @package   Xoops\Form
 * @author    trabis <trabisdementia@gmail.com>
 * @copyright 2012-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Raw extends Element
{

    /**
     * __construct
     *
     * @param string|array $value raw value to insert into form, or array of attributes
     */
    public function __construct($value)
    {
        if (is_array($value)) {
            parent::__construct($value);
        } else {
            parent::__construct([]);
            $this->set('value', $value);
        }
    }

    /**
     * render
     *
     * @return string rendered form element
     */
    public function render()
    {
        return $this->get('value', '');
    }
}

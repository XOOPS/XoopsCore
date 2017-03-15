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
 * Button - button form element
 *
 * @category  Xoops\Form\Button
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Button extends Element
{

    /**
     * Constructor
     *
     * @param string|array $caption button caption or array of all attributes
     * @param string       $name    button name
     * @param string       $value   button value
     * @param string       $type    type of the button. Possible values: "button", "submit", or "reset"
     */
    public function __construct($caption, $name = null, $value = "", $type = "button")
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('type', $type, 'button', ['button', 'submit', 'reset']);
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('value', $value);
        }
    }

    /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $this->themeDecorateElement();

        $attributes = $this->renderAttributeString();
        return '<input ' . $attributes . $this->getExtra() .' >';
    }
}

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
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class Button extends Element
{

    /**
     * Constructor
     *
     * @param string $caption button caption
     * @param string $name    button name
     * @param string $value   button valye
     * @param string $type    type of the button. Possible values: "button", "submit", or "reset"
     */
    public function __construct($caption, $name, $value = "", $type = "button")
    {
        $this->setCaption($caption);
        $this->setAttribute('type', $type);
        $this->setAttribute('name', $name);
        $this->setValue($value);
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return (string) $this->getAttribute('type');
        //return in_array(strtolower($this->type), array("button", "submit", "reset")) ? $this->type : "button";
    }

    /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $this->addAttribute('class', 'btn');

        $attributes = $this->renderAttributeString();
        return '<input ' . $attributes . 'value="' . $this->getValue()
            . '" ' . $this->getExtra() .' >';
    }
}

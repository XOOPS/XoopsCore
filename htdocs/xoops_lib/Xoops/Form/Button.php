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

    //protected $class;

    /**
     * Type of the button. This could be either "button", "submit", or "reset"
     * @var    string
     */
    protected $type;

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
        $this->setName($name);
        $this->type = $type;
        $this->setValue($value);
        //$this->class = $class;
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return in_array(strtolower($this->type), array("button", "submit", "reset")) ? $this->type : "button";
    }

    /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $name = $this->getName();
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : " class='btn'");
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        return "<input type='" . $this->getType() . "' name='" . $name . "' title='" . $this->getTitle()
            . "' id='" . $name . "'" . $class . " value='" . $this->getValue() . "'" . $extra . ">";
    }
}

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS form element of button
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class XoopsFormButton extends XoopsFormElement
{

    //protected $_class;

    /**
     * Type of the button. This could be either "button", "submit", or "reset"
     * @var    string
     */
    protected $_type;

    /**
     * Constructor
     *
     * @param    string  $caption    Caption
     * @param    string  $name
     * @param    string  $value
     * @param    string  $type       Type of the button. Potential values: "button", "submit", or "reset"
     */
    public function __construct($caption, $name, $value = "", $type = "button")
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_type = $type;
        $this->setValue($value);
        //$this->_class = $class;
    }

    /*public function getClass()
    {
        return $this->_class;
    }*/

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return in_array(strtolower($this->_type), array("button", "submit", "reset")) ? $this->_type : "button";
    }

    /**
     * prepare HTML for output
     *
     * @return    string
     */
    public function render()
    {
        $name = $this->getName();
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : " class='btn'");
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        return "<input type='" . $this->getType() . "' name='" . $name . "' title='" . $this->getTitle() . "' id='" . $name . "'" . $class . " value='" . $this->getValue() . "'" . $extra . ">";
    }
}
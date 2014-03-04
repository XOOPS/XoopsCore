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
 * XOOPS form element of text
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A simple text field
 */
class XoopsFormText extends XoopsFormElement
{
    /**
     * Size
     *
     * @var int
     * @access private
     */
    private $_size;

    /**
     * Maximum length of the text
     *
     * @var int
     * @access private
     */

    private $_maxlength;

     /**
     * placeholder for this element
     *
     * @var string
     * @access private
     */
    private $_placeholder;

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param int $size Size
     * @param int $maxlength Maximum length of text
     * @param string $value Initial text
     * @param string $placeholder placeholder for this element.
     */
    public function __construct($caption, $name, $size, $maxlength, $value = '', $placeholder = '')
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_size = intval($size);
        $this->_maxlength = intval($maxlength);
        $this->setValue($value);
        $this->_placeholder = $placeholder;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Get maximum text length
     *
     * @return int
     */
    public function getMaxlength()
    {
        return $this->_maxlength;
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        if (empty($this->_placeholder)) {
            return '';
        }
        return $this->_placeholder;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $name = $this->getName();
        if ($this->getSize() > $this->getMaxcols()) {
            $maxcols = 5;
        } else {
            $maxcols = $this->getSize();
        }
        $class = ($this->getClass() != '' ? " class='span" . $maxcols . " " . $this->getClass() . "'" : " class='span" . $maxcols . "'");
        $list = ($this->isDatalist() != '' ? " list='list_" . $name . "'" : '');
        $pattern = ($this->getPattern() != '' ? " pattern='" . $this->getPattern() . "'" : '');
        $placeholder = ($this->getPlaceholder() != '' ? " placeholder='" . $this->getPlaceholder() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $required = ($this->isRequired() ? ' required' : '');
        return "<input type='text' name='" . $name . "' title='" . $this->getTitle() . "' id='" . $name . "'" . $class ." maxlength='" . $this->getMaxlength() . "' value='" . $this->getValue() . "'" . $list . $pattern . $placeholder . $extra . $required . ">";
    }
}
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
 * XOOPS form element of password
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Password Field
 */
class XoopsFormPassword extends XoopsFormElement
{
    /**
     * Size of the field.
     *
     * @var int
     */
    private $_size;

    /**
     * Maximum length of the text
     *
     * @var int
     */
    private $_maxlength;

    /**
     * Cache password with browser. Disabled by default for security consideration
     * Added in 2.3.1
     *
     * @var boolean
     */
    public $autoComplete = false;

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
     * @param int $size Size of the field
     * @param int $maxlength Maximum length of the text
     * @param string $value Initial value of the field.
     * @param string $placeholder placeholder for this element.
     *                                           <strong>Warning:</strong> this is readable in cleartext in the page's source!
     * @param bool $autoComplete To enable autoComplete or browser cache
     */
    public function __construct($caption, $name, $size, $maxlength, $value = '', $autoComplete = false, $placeholder = '')
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_size = intval($size);
        $this->_maxlength = intval($maxlength);
        $this->setValue($value);
        $this->autoComplete = !empty($autoComplete);
        $this->_placeholder = $placeholder;
    }

    /**
     * Get the field size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Get the max length
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
            $maxcols = $this->getMaxcols();
        } else {
            $maxcols = $this->getSize();
        }
        $class = ($this->getClass() != '' ? " class='span" . $maxcols . " " . $this->getClass() . "'" : " class='span" . $maxcols . "'");
        $pattern = ($this->getPattern() != '' ? " pattern='" . $this->getPattern() . "'" : '');
        $placeholder = ($this->getPlaceholder() != '' ? " placeholder='" . $this->getPlaceholder() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $autocomplete = ($this->autoComplete ? '' : " autocomplete='off'");
        $required = ($this->isRequired() ? ' required' : '');
        return "<input type='password' name='" . $name . "' title='" . $this->getTitle() . "' id='" . $name . "'" . $class ." maxlength='" . $this->getMaxlength() . "' value='" . $this->getValue() . "'" . $pattern . $placeholder . $extra . $autocomplete . $required . ">";
    }
}
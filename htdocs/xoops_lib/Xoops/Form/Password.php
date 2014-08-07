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
 * Password - a password entry element
 *
 * @category  Xoops\Form\Password
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class Password extends Element
{
    /**
     * Size of the field.
     *
     * @var int
     */
    private $size;

    /**
     * Maximum length of the text
     *
     * @var int
     */
    private $maxlength;

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
    private $placeholder;

    /**
     * __construct
     *
     * @param string  $caption      Caption
     * @param string  $name         name attribute
     * @param integer $size         Size of the field
     * @param integer $maxlength    Maximum length of the text
     * @param string  $value        Initial value of the field - *Warning:* readable in cleartext in the page!
     * @param boolean $autoComplete To enable autoComplete or browser cache
     * @param string  $placeholder  placeholder for this element.
     */
    public function __construct(
        $caption,
        $name,
        $size,
        $maxlength,
        $value = '',
        $autoComplete = false,
        $placeholder = ''
    ) {
        $this->setCaption($caption);
        $this->setName($name);
        $this->size = intval($size);
        $this->maxlength = intval($maxlength);
        $this->setValue($value);
        $this->autoComplete = !empty($autoComplete);
        $this->placeholder = $placeholder;
    }

    /**
     * Get the field size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get the max length
     *
     * @return int
     */
    public function getMaxlength()
    {
        return $this->maxlength;
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        if (empty($this->placeholder)) {
            return '';
        }
        return $this->placeholder;
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
        $class = ($this->getClass() != ''
            ? " class='span" . $maxcols . " " . $this->getClass() . "'"
            : " class='span" . $maxcols . "'");
        $pattern = ($this->getPattern() != '' ? " pattern='" . $this->getPattern() . "'" : '');
        $placeholder = ($this->getPlaceholder() != '' ? " placeholder='" . $this->getPlaceholder() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $autocomplete = ($this->autoComplete ? '' : " autocomplete='off'");
        $required = ($this->isRequired() ? ' required' : '');
        return "<input type='password' name='" . $name . "' title='" . $this->getTitle()
            . "' id='" . $name . "'" . $class ." maxlength='" . $this->getMaxlength()
            . "' value='" . $this->getValue() . "'" . $pattern . $placeholder
            . $extra . $autocomplete . $required . ">";
    }
}

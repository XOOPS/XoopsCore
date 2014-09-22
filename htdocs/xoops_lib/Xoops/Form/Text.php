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
 * Text - a simple text field
 *
 * @category  Xoops\Form\Text
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class Text extends Element
{
    /**
     * Size
     *
     * @var int
     * @access private
     */
    //private $size;

    /**
     * Maximum length of the text
     *
     * @var int
     * @access private
     */

    //private $maxlength;

     /**
     * placeholder for this element
     *
     * @var string
     * @access private
     */
    //private $placeholder;

    /**
     * __construct
     *
     * @param string  $caption     Caption
     * @param string  $name        name attribute
     * @param integer $size        Size
     * @param integer $maxlength   Maximum length of text
     * @param string  $value       Initial text
     * @param string  $placeholder placeholder for this element.
     */
    public function __construct($caption, $name, $size, $maxlength, $value = '', $placeholder = '')
    {
        $this->setAttribute('type', 'text');
        $this->setCaption($caption);
        $this->setAttribute('name', $name);
        $this->setAttribute('size', intval($size));
        $this->setAttribute('maxlength', intval($maxlength));
        $this->setValue($value);
        if (!empty($placeholder)) {
            $this->setAttribute('placeholder', $placeholder);
        }
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        return (int) $this->getAttribute('size');
    }

    /**
     * Get maximum text length
     *
     * @return int
     */
    public function getMaxlength()
    {
        return (int) $this->getAttribute('maxlength');
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return (string) $this->getAttribute('placeholder');
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        if ($this->getSize() > $this->getMaxcols()) {
            $maxcols = $this->getMaxcols();
        } else {
            $maxcols = $this->getSize();
        }
        $this->addAttribute('class', 'span' . $maxcols);
        $dlist = $this->isDatalist();
        if (!empty($dlist)) {
            $this->addAttribute('list', 'list_' . $this->getName());
        }

        $attributes = $this->renderAttributeString();
        return '<input ' . $attributes . 'value="'
            . $this->getValue() . '" ' . $this->getExtra() .' >';
    }
}

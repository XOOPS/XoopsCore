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
 * Url - text element for URL
 *
 * @category  Xoops\Form\Url
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class Url extends Text
{
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
        parent::__construct($caption, $name, $size, $maxlength, $value, $placeholder);
        $this->setPattern('https?://.+', \XoopsLocale::STARTING_WITH_HTTP_OR_HTTPS);
    }

    /**
     * render
     *
     * @return string rendered element
     */
    public function render()
    {
        $name = $this->getName();
        if ($this->getSize() > $this->getMaxcols()) {
            $maxcols = 5;
        } else {
            $maxcols = $this->getSize();
        }
        $class = ($this->getClass() != ''
            ? " class='span" . $maxcols . " " . $this->getClass() . "'"
            : " class='span" . $maxcols . "'");
        $list = ($this->isDatalist() != '' ? " list='list_" . $name . "'" : '');
        $pattern = ($this->getPattern() != '' ? " pattern='" . $this->getPattern() . "'" : '');
        $placeholder = ($this->getPlaceholder() != '' ? " placeholder='" . $this->getPlaceholder() . "'" : '');
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $required = ($this->isRequired() ? ' required' : '');
        return "<input type='url' name='" . $name . "' title='" . $this->getTitle()
            . "' id='" . $name . "'" . $class ." maxlength='" . $this->getMaxlength()
            . "' value='" . $this->getValue() . "'" . $list . $pattern . $placeholder
            . $extra . $required . ">";
    }
}

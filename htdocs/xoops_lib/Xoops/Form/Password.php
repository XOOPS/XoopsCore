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
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Password extends Element
{
    /**
     * __construct
     *
     * @param string|array $caption      Caption or array of all attributes
     * @param string       $name         name attribute
     * @param integer      $size         Size of the field
     * @param integer      $maxlength    Maximum length of the text
     * @param string       $value        Initial value of the field - *Warning:* readable in cleartext in the page!
     * @param string       $autoComplete Turn autoComplete in browser 'on' or 'off'
     * @param string       $placeholder  placeholder for this element.
     */
    public function __construct(
        $caption,
        $name = null,
        $size = 32,
        $maxlength = 64,
        $value = '',
        $autoComplete = 'off',
        $placeholder = ''
    ) {
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->setIfNotSet('size', 32);
            $this->setIfNotSet('maxlength', 64);
            $this->setIfNotSet('autocomplete', 'off');
        } else {
            parent::__construct([]);
            $this->setCaption($caption);
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('size', empty($size) ? 32 : (int) $size);
            $this->set('maxlength', empty($maxlength) ? 64 : (int) $maxlength);
            $this->setValue($value);
            $this->setWithDefaults('autocomplete', $autoComplete, 'off', ['on', 'off']);
            if (!empty($placeholder)) {
                $this->set('placeholder', $placeholder);
            }
        }
        $this->set('type', 'password');
    }

    /**
     * Get the field size
     *
     * @return int
     */
    public function getSize()
    {
        return (int) $this->get('size', 32);
    }

    /**
     * Get the max length
     *
     * @return int
     */
    public function getMaxlength()
    {
        return (int) $this->get('maxlength', 64);
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $this->themeDecorateElement();

        $attributes = $this->renderAttributeString();
        return '<input ' . $attributes . $this->getExtra() .' >';
    }
}

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
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Text extends Element
{
    /**
     * __construct
     *
     * @param string|array $caption     Caption or array of all attributes
     * @param string       $name        name attribute
     * @param integer      $size        Size
     * @param integer      $maxlength   Maximum length of text
     * @param string       $value       Initial text
     * @param string       $placeholder placeholder for this element.
     */
    public function __construct($caption, $name = null, $size = 10, $maxlength = 64, $value = '', $placeholder = '')
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('name', $name, 'name_error');
            $this->setWithDefaults('size', $size, 10);
            $this->setWithDefaults('maxlength', $maxlength, 64);
            $this->set('value', $value);
            $this->setIfNotEmpty('placeholder', $placeholder);
        }
        $this->setIfNotSet('type', 'text');
        $this->setIfNotSet('value', '');
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        return (int) $this->get('size');
    }

    /**
     * Get maximum text length
     *
     * @return int
     */
    public function getMaxlength()
    {
        return (int) $this->get('maxlength');
    }

    /**
     * Get placeholder for this element
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return (string) $this->get('placeholder');
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $this->themeDecorateElement();
        $dataList = $this->isDatalist();
        if (!empty($dataList)) {
            $this->add('list', 'list_' . $this->getName());
        }

        $attributes = $this->renderAttributeString();
        return '<input ' . $attributes . ' ' . $this->getExtra() .' >';
    }
}

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
 * TextArea - a text area element
 *
 * @category  Xoops\Form\TextArea
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class TextArea extends Element
{
    /**
     * Constructor
     *
     * @param string|array $caption     Caption or array of all attributes
     * @param string       $name        name
     * @param string       $value       initial content
     * @param integer      $rows        number of rows
     * @param integer      $cols        number of columns
     * @param string       $placeholder placeholder for this element.
     */
    public function __construct($caption, $name = null, $value = "", $rows = 5, $cols = 50, $placeholder = '')
    {
        if (is_array($caption)) {
            parent::__construct($caption);
            $this->setIfNotSet('rows', 5);
            $this->setIfNotSet('cols', 50);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('name', $name, 'name_error');
            $this->setWithDefaults('rows', $rows, 5);
            $this->setWithDefaults('cols', $cols, 50);
            $this->setWithDefaults('value', $value, '');
            $this->setIfNotEmpty('placeholder', $placeholder);
        }
    }

    /**
     * get number of rows
     *
     * @return int
     */
    public function getRows()
    {
        return (int) $this->get('rows');
    }

    /**
     * Get number of columns
     *
     * @return int
     */
    public function getCols()
    {
        return (int) $this->get('cols');
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
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $this->suppressRender(['value']);

        $this->themeDecorateElement();

        $attributes = $this->renderAttributeString();
        return '<textarea ' . $attributes . ' ' . $this->getExtra() .' >'
            . $this->getValue() . '</textarea>';
    }
}

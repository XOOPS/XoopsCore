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
 * Label - a field label
 *
 * @category  Xoops\Form\Label
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Label extends Element
{

    /**
     * Constructor
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $value   Text
     * @param string       $id      id of rendered element
     */
    public function __construct($caption = '', $value = '', $id = '')
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('value', $value, '');
            $this->setIfNotEmpty('id', $id);
        }
        $this->set('name', $id);
    }

    /**
     * render
     *
     * @return string rendered form element
     */
    public function render()
    {
        $this->suppressRender(['name', 'value']);
        $attributes = $this->renderAttributeString();
        $ret = '<div ' . $attributes . '>' . $this->getValue() . '</div>';
        return $ret;
    }
}

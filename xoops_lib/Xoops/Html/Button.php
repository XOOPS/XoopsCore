<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Html;

/**
 * Img - Render an html img tag
 *
 * @category  Xoops\Html\Img
 * @package   Xoops\Html
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class Button extends Attributes
{
    /**
     * __construct
     *
     * @param array $attributes array of attribute name => value pairs for button tag.
     *                          Pass the button text as 'value' attribute.
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * render a button
     *
     * @return string
     */
    public function render()
    {
        $this->suppressRender(['value']);
        $tag = '<button ' . $this->renderAttributeString() . '>'
            . $this->get('value', '')
            . '</button>';

        return $tag;
    }
}

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
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Img extends Attributes
{
    /**
     * __construct
     *
     * @param array $attributes array of attribute name => value pairs for img tag
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * render
     *
     * @return string
     */
    public function render()
    {
        $tag = '<img ' . $this->renderAttributeString() . ' />';

        return $tag;
    }
}

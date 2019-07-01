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
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Url extends Text
{
    /**
     * __construct
     *
     * @param string  $caption     Caption
     * @param string  $name        name attribute
     * @param int $size        Size
     * @param int $maxlength   Maximum length of text
     * @param string  $value       Initial text
     * @param string  $placeholder placeholder for this element.
     */
    public function __construct($caption, $name, $size, $maxlength, $value = '', $placeholder = '')
    {
        parent::__construct($caption, $name, $size, $maxlength, $value, $placeholder);
        $this->set('type', 'url');
        $this->setPattern('https?://.+', \XoopsLocale::STARTING_WITH_HTTP_OR_HTTPS);
    }
}

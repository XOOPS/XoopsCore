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
 * File - a file upload field
 *
 * @category  Xoops\Form\File
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class File extends Element
{
    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name    name attribute
     */
    public function __construct($caption, $name)
    {
        $this->setCaption($caption);
        $this->setAttribute('type', 'file');
        $this->setAttribute('name', $name);
    }

    /**
     * prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $attributes = $this->renderAttributeString();

        return '<input ' . $attributes . ' ' . $this->getExtra() . ' />'
            . '<input type="hidden" name="xoops_upload_file[]" id="xoops_upload_file[]" value="'
            . $this->getName() . '">';
    }
}

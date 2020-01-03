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
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class File extends Element
{
    /**
     * Constructor
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $name    name attribute
     */
    public function __construct($caption, $name = null)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct();
            $this->setCaption($caption);
            $this->set('name', $name);
        }
        $this->set('type', 'file');
    }

    /**
     * defaultRender
     *
     * @return string rendered form element
     */
    public function defaultRender()
    {
        $attributes = $this->renderAttributeString();

        return '<input ' . $attributes . ' ' . $this->getExtra() . ' />'
            . '<input type="hidden" name="xoops_upload_file[]" id="xoops_upload_file[]" value="'
            . $this->getName() . '">';
    }
}

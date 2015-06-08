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
 * Hidden - a hidden field
 *
 * @category  Xoops\Form\Hidden
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
*/
class Hidden extends Element
{

    /**
     * Constructor
     *
     * @param string $name  name attribute
     * @param string $value value attribute
     */
    public function __construct($name, $value)
    {
        $this->setAttribute('type', 'hidden');
        $this->setAttribute('name', $name);
        $this->setHidden();
        $this->setValue($value);
        $this->setCaption('');
    }

    /**
     * render
     *
     * @return string rendered form element
     */
    public function render()
    {
        $attributes = $this->renderAttributeString();
        return '<input ' . $attributes . 'value="'
            . $this->getValue() . '" ' . $this->getExtra() .' >' . NWLINE;
    }
}

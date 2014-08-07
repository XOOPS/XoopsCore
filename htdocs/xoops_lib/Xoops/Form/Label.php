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
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class Label extends Element
{

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $value   Text
     * @param string $name    id of rendered element
     */
    public function __construct($caption = '', $value = '', $name = '')
    {
        $this->setCaption($caption);
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * render
     *
     * @return string rendered form element
     */
    public function render()
    {
        $idName = $this->getName();
        $id = empty($idName) ? '' : ' id="' . $idName . '"';
        $ret = '<span' . $id . '>' . $this->getValue() . '</span>';
        return $ret;
    }
}

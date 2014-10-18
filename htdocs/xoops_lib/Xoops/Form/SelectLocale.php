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
 * SelectLocale - a select field with available locales
 *
 * @category  Xoops\Form\SelectLocale
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
 */
class SelectLocale extends Select
{
    /**
     * Constructor
     *
     * @param string  $caption caption
     * @param string  $name    name
     * @param mixed   $value   Pre-selected value (or array of them). Valid value is
     *                         any name of a XOOPS_ROOT_PATH."/locale/" subdirectory.
     * @param integer $size    Number of rows. "1" makes a drop-down-list.
     */
    public function __construct($caption, $name, $value = null, $size = 1)
    {
        parent::__construct($caption, $name, $value, $size);
        $this->addOptionArray(\XoopsLists::getLocaleList());
    }
}

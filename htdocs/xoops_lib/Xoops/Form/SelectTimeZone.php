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
 * SelectTimeZone - a select box with time zones
 *
 * @category  Xoops\Form\SelectTimeZone
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class SelectTimeZone extends Select
{
    /**
     * Constructor
     *
     * @param string  $caption caption
     * @param string  $name    name
     * @param mixed   $value   Pre-selected value (or array of them).
     *                         Legal values are "-12" to "12" with some ".5"s strewn in ;-)
     * @param integer $size    Number of rows. "1" makes a drop-down-box.
     */
    public function __construct($caption, $name, $value = null, $size = 1)
    {
        parent::__construct($caption, $name, $value, $size);
        $this->addOptionArray(\XoopsLists::getTimeZoneList());
    }
}

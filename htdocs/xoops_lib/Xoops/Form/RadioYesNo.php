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
 * RadioYesNo - Yes/No radio buttons.
 *
 * A pair of radio buttons labelled YES and NO with values 1 and 0
 *
 * @category  Xoops\Form\RadioYesNo
 * @package   Xoops\Form
 * @author    Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @copyright 2001-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0.0
 */
class RadioYesNo extends Radio
{
    /**
     * Constructor
     *
     * @param string      $caption caption
     * @param string      $name    element name
     * @param string|null $value   Pre-selected value, can be "0" (No) or "1" (Yes)
     * @param string      $yes     String for "Yes"
     * @param string      $no      String for "No"
     */
    public function __construct($caption, $name, $value = null, $yes = \XoopsLocale::YES, $no = \XoopsLocale::NO)
    {
        parent::__construct($caption, $name, $value, true);
        $this->addOption(1, $yes);
        $this->addOption(0, $no);
    }
}

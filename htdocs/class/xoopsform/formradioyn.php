<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS form element of radio yn
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Yes/No radio buttons.
 *
 * A pair of radio buttons labelled _YES and _NO with values 1 and 0
 */
class XoopsFormRadioYN extends XoopsFormRadio
{
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param string|null $value Pre-selected value, can be "0" (No) or "1" (Yes)
     * @param string $yes String for "Yes"
     * @param string $no String for "No"
     */
    public function __construct($caption, $name, $value = null, $yes = XoopsLocale::YES, $no = XoopsLocale::NO)
    {
        parent::__construct($caption, $name, $value, true);
        $this->addOption(1, $yes);
        $this->addOption(0, $no);
    }
}
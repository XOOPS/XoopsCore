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
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class RadioYesNo extends Radio
{
    /**
     * Constructor
     *
     * @param string|array $caption Caption or array of all attributes
     *                               Control attributes:
     *                                   :yes label for '1' response
     *                                   :no  label for '0' response
     * @param string       $name    element name
     * @param string|null  $value   Pre-selected value, can be "0" (No) or "1" (Yes)
     * @param string       $yes     String for "Yes"
     * @param string       $no      String for "No"
     */
    public function __construct($caption, $name = null, $value = null, $yes = \XoopsLocale::YES, $no = \XoopsLocale::NO)
    {
        parent::__construct($caption, $name, $value, true);
        if (is_array($caption)) {
            $this->set(':inline');
            $this->setIfNotSet(':yes', \XoopsLocale::YES);
            $this->setIfNotSet(':no', \XoopsLocale::NO);
        } else {
            $this->setWithDefaults(':yes', $yes, \XoopsLocale::YES);
            $this->setWithDefaults(':no', $no, \XoopsLocale::NO);
        }
        $this->addOptionArray([1 => $this->get(':yes'), 0 => $this->get(':no')]);
    }
}

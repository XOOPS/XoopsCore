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
 * SelectMatchOption - a selection box with options for matching search terms
 *
 * @category  Xoops\Form\SelectMatchOption
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SelectMatchOption extends Select
{
    /**
     * Constructor
     *
     * @param string|array $caption caption
     * @param string       $name    name
     * @param mixed        $value   Pre-selected value (or array of them).
     * @param integer      $size    Number of rows. "1" makes a drop-down-list
     */
    public function __construct($caption, $name = null, $value = null, $size = 1)
    {
        parent::__construct($caption, $name, $value, $size, false);
        $this->addOption(XOOPS_MATCH_START, \XoopsLocale::STARTS_WITH);
        $this->addOption(XOOPS_MATCH_END, \XoopsLocale::ENDS_WITH);
        $this->addOption(XOOPS_MATCH_EQUAL, \XoopsLocale::MATCHES);
        $this->addOption(XOOPS_MATCH_CONTAIN, \XoopsLocale::CONTAINS);
    }
}

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
 * SelectLanguage - a select field with available languages
 *
 * @category  Xoops\Form\SelectLanguage
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SelectLanguage extends Select
{
    /**
     * Constructor
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $name    name
     * @param mixed        $value   Pre-selected value (or array of them). Valid values are
     *                               Legal is any name of a XOOPS_ROOT_PATH."/language/" subdirectory.
     * @param integer      $size    Number of rows. "1" makes a drop-down-list.
     */
    public function __construct($caption, $name = '', $value = null, $size = 1)
    {
        parent::__construct($caption, $name, $value, $size);
        $this->addOptionArray(\XoopsLists::getLangList());
    }
}

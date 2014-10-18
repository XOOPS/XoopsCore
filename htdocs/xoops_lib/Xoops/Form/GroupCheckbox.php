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
 * GroupCheckbox - select group(s) using checkboxes
 *
 * @category  Xoops\Form\GroupCheckbox
 * @package   Xoops\Form
 * @author    John Neill <catzwolf@xoops.org>
 * @copyright 2001-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.3.0
 */
class GroupCheckbox extends Checkbox
{
    /**
     * Constructor
     *
     * @param string  $caption  caption
     * @param string  $name     element name
     * @param mixed   $value    Pre-selected value (or array of them).
     * @param integer $size     Number or rows. "1" makes a drop-down-list.
     * @param boolean $multiple Allow multiple selections?
     */
    public function __construct($caption, $name, $value = null, $size = 1, $multiple = false)
    {
        parent::__construct($caption, $name, $value, true);
        //$this->columns = 3;
        $this->userGroups = \Xoops::getInstance()->getHandlerMember()->getGroupList();
        foreach ($this->userGroups as $group_id => $group_name) {
            $this->addOption($group_id, $group_name);
        }
    }
}

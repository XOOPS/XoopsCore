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
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class GroupCheckbox extends Checkbox
{
    /**
     * Constructor
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $name    element name
     * @param mixed        $value   Pre-selected value (or array of them).
     */
    public function __construct($caption, $name = null, $value = null)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('value', $value);
        }
        $this->set(':inline');
        $userGroups = \Xoops::getInstance()->getHandlerMember()->getGroupList();
        $this->addOptionArray($userGroups);
    }
}

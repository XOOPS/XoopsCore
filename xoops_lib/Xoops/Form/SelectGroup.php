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

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\FixedGroups;

/**
 * SelectGroup - a select field with a choice of available groups
 *
 * @category  Xoops\Form\SelectGroup
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class SelectGroup extends Select
{
    /**
     * Constructor
     *
     * @param string|array $caption      Caption or array of all attributes
     *                                    Control attributes:
     *                                        :include_anon true to include anonymous groups
     * @param string       $name         element name
     * @param boolean      $include_anon Include group "anonymous"?
     * @param mixed        $value        Pre-selected value (or array of them).
     * @param integer      $size         Number or rows. "1" makes a drop-down-list.
     * @param boolean      $multiple     Allow multiple selections?
     */
    public function __construct($caption, $name = null, $include_anon = false, $value = null, $size = 1, $multiple = false)
    {
        parent::__construct($caption, $name, $value, $size, $multiple);
        $member_handler = \Xoops::getInstance()->getHandlerMember();
        if ($include_anon || $this->get(':include_anon', false)) {
            $this->addOptionArray($member_handler->getGroupList());
        } else {
            $this->addOptionArray($member_handler->getGroupList(new Criteria('groupid', FixedGroups::ANONYMOUS, '!=')));
        }
    }
}

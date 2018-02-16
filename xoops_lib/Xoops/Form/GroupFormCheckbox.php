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
 * GroupFormCheckbox - checkbox options for a group permission form
 *
 * @category  Xoops\Form\GroupFormCheckbox
 * @package   Xoops\Form
 * @author    Xoops Development Team
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class GroupFormCheckbox extends Element
{
    /**
     * Group ID
     *
     * @var int
     */
    private $groupId;

    /**
     * Option tree
     *
     * @var array
     */
    private $optionTree = array();

    /**
     * __construct
     *
     * @param string|array $caption Caption or array of all attributes
     * @param string       $name    element name
     * @param integer      $groupId group id
     * @param mixed        $values  values
     */
    public function __construct($caption, $name = null, $groupId = null, $values = null)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setCaption($caption);
            $this->setName($name);
            $this->setWithDefaults('value', (array) $values, []);
            $this->set(':groupid', $groupId);
        }
        $this->groupId = $this->get(':groupid', 0);
    }

    /**
     * Sets the tree structure of items
     *
     * @param array $optionTree options
     *
     * @return void
     */
    public function setOptionTree(&$optionTree)
    {
        $this->optionTree = $optionTree;
    }

    /**
     * Renders checkbox options for this group
     *
     * @return string
     */
    public function render()
    {
        $ele_name = $this->getName();
        $ret = '<table class="outer"><tr><td class="odd"><table><tr>';
        $cols = 1;

        foreach ($this->optionTree[0]['children'] as $topitem) {
            if ($cols > 4) {
                $ret .= '</tr><tr>';
                $cols = 1;
            }
            $tree = '<td valign="top">';
            $prefix = '';
            $this->renderOptionTree($tree, $this->optionTree[$topitem], $prefix);
            $ret .= $tree . '</td>';
            ++$cols;
        }
        $ret .= '</tr></table></td><td class="even" valign="top">';
        $option_ids = array();
        foreach (array_keys($this->optionTree) as $id) {
            if (!empty($id)) {
                $option_ids[] = "'" . $ele_name . '[groups][' . $this->groupId . '][' . $id . ']' . "'";
            }
        }
        $checkAllButtonId = $ele_name . '[checkallbtn][' . $this->groupId . ']';
        $option_ids_str = implode(', ', $option_ids);
        $ret .= \XoopsLocale::ALL . " <input id=\"" . $checkAllButtonId . "\" type=\"checkbox\" value=\"\" "
            . "onclick=\"var optionids = new Array(" . $option_ids_str . "); "
            . "xoopsCheckAllElements(optionids, '" . $checkAllButtonId . "');\" />";
        $ret .= '</td></tr></table>';
        return $ret;
    }

    /**
     * Renders checkbox options for an item tree
     *
     * @param string $tree      rendered tree by reference
     * @param array  $option    option
     * @param string $prefix    prefix
     * @param array  $parentIds parent ids
     *
     * @return void append
     */
    private function renderOptionTree(&$tree, $option, $prefix, $parentIds = array())
    {
        $elementName = $this->getName();
        $tree .= $prefix . "<input type=\"checkbox\" name=\"" . $elementName . "[groups]["
            . $this->groupId . "][" . $option['id'] . "]\" id=\"" . $elementName
            . "[groups][" . $this->groupId . "][" . $option['id'] . "]\" onclick=\"";
        // If there are parent elements, add javascript that will
        // make them selected when this element is checked to make
        // sure permissions to parent items are added as well.
        foreach ($parentIds as $pid) {
            $parent_ele = $elementName . '[groups][' . $this->groupId . '][' . $pid . ']';
            $tree .= "var ele = xoopsGetElementById('" . $parent_ele
                . "'); if(ele.checked != true) {ele.checked = this.checked;}";
        }
        // If there are child elements, add javascript that will
        // make them unchecked when this element is unchecked to make
        // sure permissions to child items are not added when there
        // is no permission to this item.
        foreach ($option['allchild'] as $cid) {
            $child_ele = $elementName . '[groups][' . $this->groupId . '][' . $cid . ']';
            $tree .= "var ele = xoopsGetElementById('" . $child_ele
                . "'); if(this.checked != true) {ele.checked = false;}";
        }
        $tree .= '" value="1"';
        if (in_array($option['id'], $this->get('value', []))) {
            $tree .= ' checked="checked"';
        }
        $tree .= " />" . $option['name'] . "<input type=\"hidden\" name=\"" . $elementName . "[parents]["
            . $option['id'] . "]\" value=\"" . implode(':', $parentIds) . "\" /><input type=\"hidden\" name=\""
            . $elementName . "[itemname][" . $option['id'] . "]\" value=\""
            . htmlspecialchars($option['name']) . "\" /><br />\n";
        if (isset($option['children'])) {
            foreach ($option['children'] as $child) {
                array_push($parentIds, $option['id']);
                $this->renderOptionTree($tree, $this->optionTree[$child], $prefix . '&nbsp;-', $parentIds);
            }
        }
    }
}

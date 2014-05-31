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
 * XOOPS group perm form
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Renders a form for setting module specific group permissions
 */
class XoopsGroupPermForm extends XoopsForm
{
    /**
     * Module ID
     *
     * @var int
     */
    private $_modid;

    /**
     * Tree structure of items
     *
     * @var array
     */
    private $_itemTree;

    /**
     * Name of permission
     *
     * @var string
     */
    private $_permName;

    /**
     * Description of permission
     *
     * @var string
     */
    private $_permDesc;

    /**
     * Whether to include anonymous users
     *
     * @var bool
     */
    private $_showAnonymous;

    /**
     * Constructor
     *
     * @param string $title
     * @param int $modid
     * @param string $permname
     * @param string $permdesc
     * @param string $url
     * @param bool $anonymous
     */
    public function __construct($title, $modid, $permname, $permdesc, $url = '', $anonymous = true)
    {
        parent::__construct($title, 'groupperm_form', XOOPS_URL . '/modules/system/admin/groupperm.php', 'post');
        $this->_modid = intval($modid);
        $this->_permName = $permname;
        $this->_permDesc = $permdesc;
        $this->addElement(new XoopsFormHidden('modid', $this->_modid));
        $this->addElement(new XoopsFormHiddenToken($permname));
        if ($url != "") {
            $this->addElement(new XoopsFormHidden('redirect_url', $url));
        }
        $this->_showAnonymous = $anonymous;
    }

    /**
     * Adds an item to which permission will be assigned
     *
     * @param string $itemName
     * @param int $itemId
     * @param int $itemParent
     */
    public function addItem($itemId, $itemName, $itemParent = 0)
    {
        $this->_itemTree[$itemParent]['children'][] = $itemId;
        $this->_itemTree[$itemId]['parent'] = $itemParent;
        $this->_itemTree[$itemId]['name'] = $itemName;
        $this->_itemTree[$itemId]['id'] = $itemId;
    }

    /**
     * Loads all child ids for an item to be used in javascript
     *
     * @param int $itemId
     * @param array $childIds
     * @access private
     */
    private function _loadAllChildItemIds($itemId, &$childIds)
    {
        if (!empty($this->_itemTree[$itemId]['children'])) {
            $first_child = $this->_itemTree[$itemId]['children'];
            foreach ($first_child as $fcid) {
                array_push($childIds, $fcid);
                if (!empty($this->_itemTree[$fcid]['children'])) {
                    foreach ($this->_itemTree[$fcid]['children'] as $_fcid) {
                        array_push($childIds, $_fcid);
                        $this->_loadAllChildItemIds($_fcid, $childIds);
                    }
                }
            }
        }
    }

    /**
     * Renders the form
     *
     * @return string
     * @access public
     */
    public function render()
    {
        $xoops = Xoops::getInstance();
        // load all child ids for javascript codes
        foreach (array_keys($this->_itemTree) as $item_id) {
            $this->_itemTree[$item_id]['allchild'] = array();
            $this->_loadAllChildItemIds($item_id, $this->_itemTree[$item_id]['allchild']);
        }
        $gperm_handler = $xoops->getHandlerGroupperm();
        $member_handler = $xoops->getHandlerMember();
        $glist = $member_handler->getGroupList();
        foreach (array_keys($glist) as $i) {
            if ($i == XOOPS_GROUP_ANONYMOUS && !$this->_showAnonymous) {
                continue;
            }
            // get selected item id(s) for each group
            $selected = $gperm_handler->getItemIds($this->_permName, $i, $this->_modid);
            $ele = new XoopsGroupFormCheckBox($glist[$i], 'perms[' . $this->_permName . ']', $i, $selected);
            $ele->setOptionTree($this->_itemTree);
            $this->addElement($ele);
            unset($ele);
        }
        $tray = new XoopsFormElementTray('');
        $tray->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
        $tray->addElement(new XoopsFormButton('', 'reset', XoopsLocale::A_CANCEL, 'reset'));
        $this->addElement($tray);

        $ret = '<h4>' . $this->getTitle() . '</h4>';
        if ($this->_permDesc) {
            $ret .= $this->_permDesc . '<br /><br />';
        }
        $ret .= '<form title="' . str_replace('"', '', $this->getTitle()) . '" name="' . $this->getName() . '" id="' . $this->getName() . '" action="' . $this->getAction() . '" method="' . $this->getMethod() . '"' . $this->getExtra() . '>' . '<table width="100%" class="outer" cellspacing="1" valign="top">';
        $elements = $this->getElements();
        $hidden = '';
        foreach (array_keys($elements) as $i) {
            if ($elements[$i] instanceof XoopsFormRaw) {
                $ret .= $elements[$i]->render();
            } elseif (!$elements[$i]->isHidden()) {
                $ret .= '<tr valign="top" align="left"><td class="head">' . $elements[$i]->getCaption();
                if ($elements[$i]->getDescription() != "") {
                    $ret .= "<br /><br /><span style='font-weight: normal;'>" . $elements[$i]->getDescription() . "</span>";
                }
                $ret .= '</td>' . '<td class="even">' . $elements[$i]->render() . '</td></tr>' . '';
            } else {
                $hidden .= $elements[$i]->render();
            }
        }
        $ret .= '</table>' . $hidden . '</form>';
        $ret .= $this->renderValidationJS(true);
        return $ret;
    }
}

/**
 * Renders checkbox options for a group permission form
 */
class XoopsGroupFormCheckBox extends XoopsFormElement
{
    /**
     * Pre-selected value(s)
     *
     * @var array
     */
     protected $_value = array();

    /**
     * Group ID
     *
     * @var int
     */
    private $_groupId;

    /**
     * Option tree
     *
     * @var array
     */
    private $_optionTree;

    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param int $groupId
     * @param mixed $values
     */
    public function __construct($caption, $name, $groupId, $values = null)
    {
        $this->setCaption($caption);
        $this->setName($name);
        if (isset($values)) {
            $this->setValue($values);
        }
        $this->_groupId = $groupId;
    }

    /**
     * Sets the tree structure of items
     *
     * @param array $optionTree
     */
    public function setOptionTree(&$optionTree)
    {
        $this->_optionTree = $optionTree;
    }

    /**
     * Renders checkbox options for this group
     *
     * @return string
     * @access public
     */
    public function render()
    {
        $ele_name = $this->getName();
        $ret = '<table class="outer"><tr><td class="odd"><table><tr>';
        $cols = 1;
        foreach ($this->_optionTree[0]['children'] as $topitem) {
            if ($cols > 4) {
                $ret .= '</tr><tr>';
                $cols = 1;
            }
            $tree = '<td valign="top">';
            $prefix = '';
            $this->_renderOptionTree($tree, $this->_optionTree[$topitem], $prefix);
            $ret .= $tree . '</td>';
            $cols++;
        }
        $ret .= '</tr></table></td><td class="even" valign="top">';
        $option_ids = array();
        foreach (array_keys($this->_optionTree) as $id) {
            if (!empty($id)) {
                $option_ids[] = "'" . $ele_name . '[groups][' . $this->_groupId . '][' . $id . ']' . "'";
            }
        }
        $checkallbtn_id = $ele_name . '[checkallbtn][' . $this->_groupId . ']';
        $option_ids_str = implode(', ', $option_ids);
        $ret .= XoopsLocale::ALL . " <input id=\"" . $checkallbtn_id . "\" type=\"checkbox\" value=\"\" onclick=\"var optionids = new Array(" . $option_ids_str . "); xoopsCheckAllElements(optionids, '" . $checkallbtn_id . "');\" />";
        $ret .= '</td></tr></table>';
        return $ret;
    }

    /**
     * Renders checkbox options for an item tree
     *
     * @param string $tree
     * @param array $option
     * @param string $prefix
     * @param array $parentIds
     */
    private function _renderOptionTree(&$tree, $option, $prefix, $parentIds = array())
    {
        $ele_name = $this->getName();
        $tree .= $prefix . "<input type=\"checkbox\" name=\"" . $ele_name . "[groups][" . $this->_groupId . "][" . $option['id'] . "]\" id=\"" . $ele_name . "[groups][" . $this->_groupId . "][" . $option['id'] . "]\" onclick=\"";
        // If there are parent elements, add javascript that will
        // make them selecteded when this element is checked to make
        // sure permissions to parent items are added as well.
        foreach ($parentIds as $pid) {
            $parent_ele = $ele_name . '[groups][' . $this->_groupId . '][' . $pid . ']';
            $tree .= "var ele = xoopsGetElementById('" . $parent_ele . "'); if(ele.checked != true) {ele.checked = this.checked;}";
        }
        // If there are child elements, add javascript that will
        // make them unchecked when this element is unchecked to make
        // sure permissions to child items are not added when there
        // is no permission to this item.
        foreach ($option['allchild'] as $cid) {
            $child_ele = $ele_name . '[groups][' . $this->_groupId . '][' . $cid . ']';
            $tree .= "var ele = xoopsGetElementById('" . $child_ele . "'); if(this.checked != true) {ele.checked = false;}";
        }
        $tree .= '" value="1"';
        if (in_array($option['id'], $this->_value)) {
            $tree .= ' checked="checked"';
        }
        $tree .= " />" . $option['name'] . "<input type=\"hidden\" name=\"" . $ele_name . "[parents][" . $option['id'] . "]\" value=\"" . implode(':', $parentIds) . "\" /><input type=\"hidden\" name=\"" . $ele_name . "[itemname][" . $option['id'] . "]\" value=\"" . htmlspecialchars($option['name']) . "\" /><br />\n";
        if (isset($option['children'])) {
            foreach ($option['children'] as $child) {
                array_push($parentIds, $option['id']);
                $this->_renderOptionTree($tree, $this->_optionTree[$child], $prefix . '&nbsp;-', $parentIds);
            }
        }
    }
}
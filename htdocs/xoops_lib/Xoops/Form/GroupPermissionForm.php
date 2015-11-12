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

use Xoops\Core\FixedGroups;

/**
 * GroupPermissionForm - a form for setting module specific group permissions
 *
 * @category  Xoops\Form\GroupPermissionForm
 * @package   Xoops\Form
 * @author    Xoops Development Team
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class GroupPermissionForm extends Form
{
    /**
     * Module ID
     *
     * @var int
     */
    private $modid;

    /**
     * Tree structure of items
     *
     * @var array
     */
    private $itemTree = array();

    /**
     * Name of permission
     *
     * @var string
     */
    private $permName;

    /**
     * Description of permission
     *
     * @var string
     */
    private $permDesc;

    /**
     * Whether to include anonymous users
     *
     * @var bool
     */
    private $showAnonymous;

    /**
     * Constructor
     *
     * @param string  $title     form title
     * @param integer $modid     module id
     * @param string  $permname  permission name
     * @param string  $permdesc  permission description
     * @param string  $url       redirect url
     * @param boolean $anonymous true to include anonymous group
     */
    public function __construct($title, $modid, $permname, $permdesc, $url = '', $anonymous = true)
    {
        parent::__construct($title, 'groupperm_form', XOOPS_URL . '/modules/system/admin/groupperm.php', 'post');
        $this->modid = (int)($modid);
        $this->permName = $permname;
        $this->permDesc = $permdesc;
        $this->addElement(new Hidden('modid', $this->modid));
        $this->addElement(new Token($permname));
        if ($url != "") {
            $this->addElement(new Hidden('redirect_url', $url));
        }
        $this->showAnonymous = $anonymous;
    }

    /**
     * Adds an item to which permission will be assigned
     *
     * @param integer $itemId     item id
     * @param string  $itemName   item name
     * @param integer $itemParent item parent
     *
     * @return void
     */
    public function addItem($itemId, $itemName, $itemParent = 0)
    {
        $this->itemTree[$itemParent]['children'][] = $itemId;
        $this->itemTree[$itemId]['parent'] = $itemParent;
        $this->itemTree[$itemId]['name'] = $itemName;
        $this->itemTree[$itemId]['id'] = $itemId;
    }

    /**
     * Loads all child ids for an item to be used in javascript
     *
     * @param int   $itemId   item id
     * @param array $childIds child ids by reference
     *
     * @return void
     */
    private function loadAllChildItemIds($itemId, &$childIds)
    {
        if (!empty($this->itemTree[$itemId]['children'])) {
            $children = $this->itemTree[$itemId]['children'];
            foreach ($children as $fcid) {
                array_push($childIds, $fcid);
                $this->loadAllChildItemIds($fcid, $childIds);
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
        $xoops = \Xoops::getInstance();
        // load all child ids for javascript codes
        foreach (array_keys($this->itemTree) as $item_id) {
            $this->itemTree[$item_id]['allchild'] = array();
            $this->loadAllChildItemIds($item_id, $this->itemTree[$item_id]['allchild']);
        }
        $gperm_handler = $xoops->getHandlerGroupPermission();
        $member_handler = $xoops->getHandlerMember();
        $glist = $member_handler->getGroupList();
        foreach (array_keys($glist) as $i) {
            if ($i == FixedGroups::ANONYMOUS && !$this->showAnonymous) {
                continue;
            }
            // get selected item id(s) for each group
            $selected = $gperm_handler->getItemIds($this->permName, $i, $this->modid);
            $ele = new GroupFormCheckbox($glist[$i], 'perms[' . $this->permName . ']', $i, $selected);
            $ele->setOptionTree($this->itemTree);
            $this->addElement($ele);
            unset($ele);
        }
        $tray = new ElementTray('');
        $tray->addElement(new Button('', 'submit', \XoopsLocale::A_SUBMIT, 'submit'));
        $tray->addElement(new Button('', 'reset', \XoopsLocale::A_CANCEL, 'reset'));
        $this->addElement($tray);

        $ret = '<h4>' . $this->getTitle() . '</h4>';
        if ($this->permDesc) {
            $ret .= $this->permDesc . '<br /><br />';
        }
        $ret .= '<form title="' . str_replace('"', '', $this->getTitle()) . '" name="'
            . $this->getName() . '" id="' . $this->getName() . '" action="' . $this->getAction()
            . '" method="' . $this->getMethod() . '"' . $this->getExtra() . '>'
            . '<table width="100%" class="outer" cellspacing="1" valign="top">';
        $elements = $this->getElements();
        $hidden = '';
        foreach (array_keys($elements) as $i) {
            if ($elements[$i] instanceof Raw) {
                $ret .= $elements[$i]->render();
            } elseif (!$elements[$i]->isHidden()) {
                $ret .= '<tr valign="top" align="left"><td class="head">' . $elements[$i]->getCaption();
                if ($elements[$i]->getDescription() != "") {
                    $ret .= "<br /><br /><span style='font-weight: normal;'>"
                        . $elements[$i]->getDescription() . "</span>";
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

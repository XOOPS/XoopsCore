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
 * page module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MenusMenus_menuForm extends Xoops\Form\ThemeForm
{
    /**
     * @param MenusMenu $obj
     */
    public function __construct(MenusMenu &$obj)
    {
        global $menu_id;
        $xoops = Xoops::getInstance();
        $helper = Menus::getInstance();
        $this_handler = $helper->getHandlerMenu();
        $decorators = MenusDecorator::getAvailableDecorators();

        $title = $obj->isNew() ? sprintf(_AM_MENUS_ADD_MENUS) : sprintf(_AM_MENUS_EDIT_MENUS);

        parent::__construct($title, 'form', 'admin_menu.php', 'post', true);

        $this->addElement(new Xoops\Form\Text(_AM_MENUS_MENU_TITLE, 'title', 50, 255, $obj->getVar('title'), ''), true);

        $this->addElement(new Xoops\Form\Text(_AM_MENUS_MENU_ALTTITLE, 'alt_title', 50, 255, $obj->getVar('alt_title'), ''));

        $this->addElement(new Xoops\Form\Text(_AM_MENUS_MENU_LINK, 'link', 50, 255, $obj->getVar('link'), ''));

        $this->addElement(new Xoops\Form\Text(_AM_MENUS_MENU_IMAGE, 'image', 50, 255, $obj->getVar('image'), ''));

        $criteria = new CriteriaCompo(new Criteria('mid', $menu_id));
        $criteria->add(new Criteria('id', $obj->getVar('id'), '<>'));
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $results = $this_handler->getAll($criteria, array('title', 'id', 'pid'));
        $parent_tree = new XoopsObjectTree($results, 'id', 'pid');
        $parent_select = $parent_tree->makeSelBox('pid', 'title', '-- ', $obj->getVar('pid'), true);
        $this->addElement(new Xoops\Form\Label(_AM_MENUS_MENU_PARENT, $parent_select));

        $formvis = new Xoops\Form\Select(_AM_MENUS_MENU_VISIBLE, "visible", $obj->getVar('visible'));
        $formvis->addOption("0", XoopsLocale::NO);
        $formvis->addOption("1", XoopsLocale::YES);
        $this->addElement($formvis);

        $formtarget = new Xoops\Form\Select(_AM_MENUS_MENU_TARGET, "target", $obj->getVar('target'));
        $formtarget->addOption("_self", _AM_MENUS_MENU_TARG_SELF);
        $formtarget->addOption("_blank", _AM_MENUS_MENU_TARG_BLANK);
        $formtarget->addOption("_parent", _AM_MENUS_MENU_TARG_PARENT);
        $formtarget->addOption("_top", _AM_MENUS_MENU_TARG_TOP);
        $this->addElement($formtarget);

        $formgroups = new Xoops\Form\SelectGroup(_AM_MENUS_MENU_GROUPS, "groups", true, $obj->getVar('groups'), 5, true);
        $formgroups->setDescription(_AM_MENUS_MENU_GROUPS_HELP);
        $this->addElement($formgroups);

        $formhooks = new Xoops\Form\Select(_AM_MENUS_MENU_ACCESS_FILTER, "hooks", $obj->getVar('hooks'), 5, true);
        $accessFilter = array();
        foreach ($decorators as $decorator) {
            $decorator->accessFilter($accessFilter);
        }
        foreach ($accessFilter as $result) {
            $formhooks->addOption($result['method'], $result['name']);
        }
        $this->addElement($formhooks);

        $formcss = new Xoops\Form\Text(_AM_MENUS_MENU_CSS, 'css', 50, 255, $obj->getVar('css'));
        $this->addElement($formcss);

        $this->addElement(new Xoops\Form\Hidden('id', $obj->getVar('id')));
        $this->addElement(new Xoops\Form\Hidden('mid', $obj->getVar('mid')));
        $this->addElement(new Xoops\Form\Hidden('op', 'save'));
        $this->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}

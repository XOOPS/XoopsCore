<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * Group Form Class
 *
 * @category  Modules/system/class/form
 * @package   SystemGroupForm
 * @author    Andricq Nicolas (AKA MusS)
 * @copyright 2000-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.0
 */
class SystemGroupForm extends Xoops\Form\ThemeForm
{
    /**
     * __construct
     *
     * @param XoopsGroup|XoopsObject &$obj group object
     */
    public function __construct(XoopsGroup &$obj)
    {
        $xoops = Xoops::getInstance();

        if ($obj->isNew()) {
            $s_cat_value = '';
            $a_mod_value = array();
            $r_mod_value = array();
            $r_block_value = array();
        } else {
            $sysperm_handler = $xoops->getHandlerGroupperm();
            $s_cat_value = $sysperm_handler->getItemIds('system_admin', $obj->getVar('groupid'));
            $member_handler = $xoops->getHandlerMember();
            $thisgroup = $member_handler->getGroup($obj->getVar('groupid'));
            $moduleperm_handler = $xoops->getHandlerGroupperm();
            $a_mod_value = $moduleperm_handler->getItemIds('module_admin', $thisgroup->getVar('groupid'));
            $r_mod_value = $moduleperm_handler->getItemIds('module_read', $thisgroup->getVar('groupid'));
            $gperm_handler = $xoops->getHandlerGroupperm();
            $r_block_value = $gperm_handler->getItemIds('block_read', $obj->getVar('groupid'));
        }
        include_once $xoops->path('/modules/system/constants.php');

        $title = $obj->isNew() ? SystemLocale::ADD_NEW_GROUP : SystemLocale::EDIT_GROUP;
        parent::__construct($title, "groupform", 'admin.php', "post", true);
        $this->setExtra('enctype="multipart/form-data"');

        $name_text = new Xoops\Form\Text(SystemLocale::GROUP_NAME, "name", 4, 50, $obj->getVar('name'));
        $desc_text = new Xoops\Form\TextArea(SystemLocale::GROUP_DESCRIPTION, "desc", $obj->getVar('description'));

        $system_catids = new Xoops\Form\ElementTray(SystemLocale::SYSTEM_ADMIN_RIGHTS, '');

        $s_cat_checkbox_all = new Xoops\Form\Checkbox('', "catbox", 1);
        $s_cat_checkbox_all->addOption('allbox', XoopsLocale::ALL);
        $s_cat_checkbox_all->setExtra(" onclick='xoopsCheckGroup(\"groupform\", \"catbox\" , \"system_catids[]\");' ");
        $s_cat_checkbox_all->setClass('xo-checkall');
        $system_catids->addElement($s_cat_checkbox_all);

        $s_cat_checkbox = new Xoops\Form\Checkbox('', "system_catids", $s_cat_value);
        //$s_cat_checkbox->columns = 6;
        $admin_dir = XOOPS_ROOT_PATH . '/modules/system/admin/';
        $dirlist = XoopsLists::getDirListAsArray($admin_dir);
        foreach ($dirlist as $file) {
            include XOOPS_ROOT_PATH . '/modules/system/admin/' . $file . '/xoops_version.php';
            if (!empty($modversion['category'])) {
                if ($xoops->getModuleConfig('active_' . $file, 'system') == 1) {
                    $s_cat_checkbox->addOption($modversion['category'], $modversion['name']);
                }
            }
            unset($modversion);
        }
        unset($dirlist);
        $system_catids->addElement($s_cat_checkbox);

        $admin_mids = new Xoops\Form\ElementTray(SystemLocale::MODULE_ADMIN_RIGHTS, '');

        $s_admin_checkbox_all = new Xoops\Form\Checkbox('', "adminbox", 1);
        $s_admin_checkbox_all->addOption('allbox', XoopsLocale::ALL);
        $s_admin_checkbox_all->setExtra(" onclick='xoopsCheckGroup(\"groupform\", \"adminbox\" , \"admin_mids[]\");' ");
        $s_admin_checkbox_all->setClass('xo-checkall');
        $admin_mids->addElement($s_admin_checkbox_all);

        $a_mod_checkbox = new Xoops\Form\Checkbox('', "admin_mids[]", $a_mod_value);
        //$a_mod_checkbox->columns = 5;
        $module_handler = $xoops->getHandlerModule();
        $criteria = new CriteriaCompo(new Criteria('hasadmin', 1));
        $criteria->add(new Criteria('isactive', 1));
        $criteria->add(new Criteria('dirname', 'system', '<>'));
        $a_mod_checkbox->addOptionArray($module_handler->getNameList($criteria));
        $admin_mids->addElement($a_mod_checkbox);

        $read_mids = new Xoops\Form\ElementTray(SystemLocale::MODULE_ACCESS_RIGHTS, '');

        $s_mod_checkbox_all = new Xoops\Form\Checkbox('', "readbox", 1);
        $s_mod_checkbox_all->addOption('allbox', XoopsLocale::ALL);
        $s_mod_checkbox_all->setExtra(" onclick='xoopsCheckGroup(\"groupform\", \"readbox\" , \"read_mids[]\");' ");
        $s_mod_checkbox_all->setClass('xo-checkall');
        $read_mids->addElement($s_mod_checkbox_all);

        $r_mod_checkbox = new Xoops\Form\Checkbox('', "read_mids[]", $r_mod_value);
        //$r_mod_checkbox->columns = 5;
        $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
        $criteria->add(new Criteria('isactive', 1));
        $r_mod_checkbox->addOptionArray($module_handler->getNameList($criteria));
        $read_mids->addElement($r_mod_checkbox);

        $criteria = new CriteriaCompo(new Criteria('isactive', 1));
        $criteria->setSort("mid");
        $criteria->setOrder("ASC");
        $module_list = $module_handler->getNameList($criteria);
        $module_list[0] = SystemLocale::CUSTOM_BLOCK;

        $block_handler = $xoops->getHandlerBlock();
        $blocks_obj = $block_handler->getDistinctObjects(
            new Criteria("mid", "('" . implode("', '", array_keys($module_list)) . "')", "IN"),
            true
        );

        $blocks_module = array();
        foreach (array_keys($blocks_obj) as $bid) {
            $title = $blocks_obj[$bid]->getVar("title");
            $blocks_module[$blocks_obj[$bid]->getVar('mid')][$blocks_obj[$bid]->getVar('bid')] =
                empty($title) ? $blocks_obj[$bid]->getVar("name") : $title;
        }
        ksort($blocks_module);

        $r_block_tray = new Xoops\Form\ElementTray(SystemLocale::BLOCK_ACCESS_RIGHTS, "<br /><br />");
        $s_checkbox_all = new Xoops\Form\Checkbox('', "blocksbox", 1);
        $s_checkbox_all->addOption('allbox', XoopsLocale::ALL);
        $s_checkbox_all->setExtra(" onclick='xoopsCheckGroup(\"groupform\", \"blocksbox\" , \"read_bids[]\");' ");
        $s_checkbox_all->setClass('xo-checkall');
        $r_block_tray->addElement($s_checkbox_all);
        foreach (array_keys($blocks_module) as $mid) {

            $new_blocks_array = array();
            foreach ($blocks_module[$mid] as $key => $value) {
                $new_blocks_array[$key] = "<a href='" . XOOPS_URL
                    . "/modules/system/admin.php?fct=blocksadmin&amp;op=edit&amp;bid={$key}' "
                    . "title='ID: {$key}' rel='external'>{$value}</a>";
            }
            $r_block_checkbox = new Xoops\Form\Checkbox(
                '<strong>' . $module_list[$mid] . '</strong><br />',
                "read_bids[]",
                $r_block_value
            );
            //$r_block_checkbox->columns = 5;
            $r_block_checkbox->addOptionArray($new_blocks_array);
            $r_block_tray->addElement($r_block_checkbox);
            unset($r_block_checkbox);
        }
        if (!$obj->isNew()) {
            $this->addElement(new Xoops\Form\Hidden('g_id', $obj->getVar('groupid')));
            $this->addElement(new Xoops\Form\Hidden("op", "groups_save_update"));
        } else {
            $this->addElement(new Xoops\Form\Hidden("op", "groups_save_add"));
        }
        $this->addElement(new Xoops\Form\Hidden('fct', 'groups'));

        $this->addElement($name_text, true);
        $this->addElement($desc_text);
        $this->addElement($system_catids);
        $this->addElement($admin_mids);
        $this->addElement($read_mids);
        $this->addElement($r_block_tray);
        $this->addElement(new Xoops\Form\Button("", "submit", XoopsLocale::A_SUBMIT, "submit"));
    }
}

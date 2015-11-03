<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\FixedGroups;

/**
 * Extended User Profile
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */
include __DIR__ . '/header.php';
// Get main instance
$system = System::getInstance();
$xoops = Xoops::getInstance();

// Get Action type
$op = $system->cleanVars($_REQUEST, 'op', 'edit', 'string');
// Call header
$xoops->header();

$admin_page = new \Xoops\Module\Admin();
$admin_page->displayNavigation('permissions.php');

$perm_desc = "";
switch ($op) {
    case "visibility":
        header("Location: visibility.php");
        break;

    case "edit":
    default:
        $title_of_form = _PROFILE_AM_PROF_EDITABLE;
        $perm_name = "profile_edit";
        $restriction = "field_edit";
        $anonymous = false;
        break;

    case "search":
        $title_of_form = _PROFILE_AM_PROF_SEARCH;
        $perm_name = "profile_search";
        $restriction = "";
        $anonymous = true;
        break;

    case "access":
        $title_of_form = _PROFILE_AM_PROF_ACCESS;
        $perm_name = "profile_access";
        $perm_desc = _PROFILE_AM_PROF_ACCESS_DESC;
        $restriction = "";
        $anonymous = true;
        break;
}

$opform = new Xoops\Form\SimpleForm('', 'opform', 'permissions.php', "get");
$op_select = new Xoops\Form\Select("", 'op', $op);
$op_select->setExtra('onchange="document.forms.opform.submit()"');
$op_select->addOption('visibility', _PROFILE_AM_PROF_VISIBLE);
$op_select->addOption('edit', _PROFILE_AM_PROF_EDITABLE);
$op_select->addOption('search', _PROFILE_AM_PROF_SEARCH);
$op_select->addOption('access', _PROFILE_AM_PROF_ACCESS);
$opform->addElement($op_select);
$opform->display();

$module_id = $xoops->module->getVar('mid');
$form = new Xoops\Form\GroupPermissionForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/permissions.php', $anonymous);

if ($op === "access") {
    $member_handler = $xoops->getHandlerMember();
    $glist = $member_handler->getGroupList();
    foreach (array_keys($glist) as $i) {
        if ($i != FixedGroups::ANONYMOUS) {
            $form->addItem($i, $glist[$i]);
        }
    }

} else {
    /* @var $profile_handler ProfileProfileHandler */
    $profile_handler = \Xoops::getModuleHelper('profile')->getHandler('profile');
    $fields = $profile_handler->loadFields();

    if ($op !== "search") {
        foreach (array_keys($fields) as $i) {
            if ($restriction == "" || $fields[$i]->getVar($restriction)) {
                $form->addItem(
                    $fields[$i]->getVar('field_id'),
                    XoopsLocale::substr($fields[$i]->getVar('field_title'), 0, 25)
                );
            }
        }
    } else {
        $searchable_types = array('textbox',
        'select',
        'radio',
        'yesno',
        'date',
        'datetime',
        'timezone',
        'language');
        foreach (array_keys($fields) as $i) {
            if (in_array($fields[$i]->getVar('field_type'), $searchable_types)) {
                $form->addItem(
                    $fields[$i]->getVar('field_id'),
                    XoopsLocale::substr($fields[$i]->getVar('field_title'), 0, 25)
                );
            }
        }
    }
}
$form->display();
$xoops->footer();

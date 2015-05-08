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
 * Extended User Profile
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
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
$op = $system->cleanVars($_REQUEST, 'op', 'list', 'string');
// Call header
$xoops->header('admin:profile/fieldlist.tpl');
// Get handler
/* @var $field_handler ProfileFieldHandler */
$field_handler = $xoops->getModuleHandler('field');
/* @var $cat_handler ProfileCategoryHandler */
$cat_handler = $xoops->getModuleHandler('category');

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('field.php');


switch ($op) {
    default:
    case "list":
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_FIELD, 'field.php?op=new', 'add');
        $admin_page->renderButton();
        $fields = $field_handler->getObjects(null, true, false);
        $modules = $xoops->getHandlerModule()->getObjectsArray(null, true);

        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight');
        $cats = $cat_handler->getObjects($criteria, true);
        unset($criteria);

        $categories[0] = _PROFILE_AM_DEFAULT;
        if (count($cats) > 0) {
            foreach (array_keys($cats) as $i) {
                $categories[$cats[$i]->getVar('cat_id')] = $cats[$i]->getVar('cat_title');
            }
        }
        $xoops->tpl()->assign('categories', $categories);
        unset($categories);
        $valuetypes = array(
            XOBJ_DTYPE_ARRAY => _PROFILE_AM_ARRAY, XOBJ_DTYPE_EMAIL => _PROFILE_AM_EMAIL,
            XOBJ_DTYPE_INT => _PROFILE_AM_INT, XOBJ_DTYPE_TXTAREA => _PROFILE_AM_TXTAREA,
            XOBJ_DTYPE_TXTBOX => _PROFILE_AM_TXTBOX, XOBJ_DTYPE_URL => _PROFILE_AM_URL,
            XOBJ_DTYPE_OTHER => _PROFILE_AM_OTHER, XOBJ_DTYPE_MTIME => _PROFILE_AM_DATE
        );

        $fieldtypes = array(
            'checkbox' => _PROFILE_AM_CHECKBOX, 'group' => _PROFILE_AM_GROUP, 'group_multi' => _PROFILE_AM_GROUPMULTI,
            'language' => _PROFILE_AM_LANGUAGE, 'radio' => _PROFILE_AM_RADIO, 'select' => _PROFILE_AM_SELECT,
            'select_multi' => _PROFILE_AM_SELECTMULTI, 'textarea' => _PROFILE_AM_TEXTAREA,
            'dhtml' => _PROFILE_AM_DHTMLTEXTAREA, 'textbox' => _PROFILE_AM_TEXTBOX, 'timezone' => _PROFILE_AM_TIMEZONE,
            'yesno' => _PROFILE_AM_YESNO, 'date' => _PROFILE_AM_DATE, 'datetime' => _PROFILE_AM_DATETIME,
            'longdate' => _PROFILE_AM_LONGDATE, 'theme' => _PROFILE_AM_THEME, 'autotext' => _PROFILE_AM_AUTOTEXT,
            'rank' => _PROFILE_AM_RANK
        );
        $categories = array();
        foreach (array_keys($fields) as $i) {
            $fields[$i]['canEdit'] = $fields[$i]['field_config'] || $fields[$i]['field_show']
                || $fields[$i]['field_edit'];
            $fields[$i]['canDelete'] = $fields[$i]['field_config'];
            $fields[$i]['fieldtype'] = $fieldtypes[$fields[$i]['field_type']];
            $fields[$i]['valuetype'] = $valuetypes[$fields[$i]['field_valuetype']];
            $categories[$fields[$i]['cat_id']][] = $fields[$i];
            $weights[$fields[$i]['cat_id']][] = $fields[$i]['field_weight'];
        }
        //sort fields order in categories
        foreach (array_keys($categories) as $i) {
            array_multisort($weights[$i], SORT_ASC, array_keys($categories[$i]), SORT_ASC, $categories[$i]);
        }
        ksort($categories);
        $xoops->tpl()->assign('fieldcategories', $categories);
        $xoops->tpl()->assign('token', $xoops->security()->getTokenHTML());
        $xoops->tpl()->assign('fieldlist', true);
        break;

    case "new":
        $admin_page->addItemButton(_PROFILE_AM_FIELD_LIST, 'field.php', 'application-view-detail');
        $admin_page->renderButton();
        $obj = $field_handler->create();
        $form = $xoops->getModuleForm($obj, 'field');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case "edit":
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_FIELD, 'field.php?op=new', 'add');
        $admin_page->addItemButton(_PROFILE_AM_FIELD_LIST, 'field.php', 'application-view-detail');
        $admin_page->renderButton();
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $field_handler->get($id);
            $form = $xoops->getModuleForm($obj, 'field');
            $xoops->tpl()->assign('form', $form->render());
        } else {
            $xoops->redirect('field.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;

    case "reorder":
        if (!$xoops->security()->check()) {
            $xoops->redirect('field.php', 3, implode(',', $xoops->security()->getErrors()));
        }
        if (isset($_POST['field_ids']) && count($_POST['field_ids']) > 0) {
            $oldweight = $_POST['oldweight'];
            $oldcat = $_POST['oldcat'];
            $category = $_POST['category'];
            $weight = $_POST['weight'];
            $ids = array();
            foreach ($_POST['field_ids'] as $field_id) {
                if ($oldweight[$field_id] != $weight[$field_id] || $oldcat[$field_id] != $category[$field_id]) {
                    //if field has changed
                    $ids[] = intval($field_id);
                }
            }
            if (count($ids) > 0) {
                $errors = array();
                //if there are changed fields, fetch the fieldcategory objects
                $fields = $field_handler->getObjects(
                    new Criteria('field_id', "(" . implode(',', $ids) . ")", "IN"),
                    true
                );
                foreach ($ids as $i) {
                    $fields[$i]->setVar('field_weight', intval($weight[$i]));
                    $fields[$i]->setVar('cat_id', intval($category[$i]));
                    if (!$field_handler->insertFields($fields[$i])) {
                        $errors = array_merge($errors, $fields[$i]->getErrors());
                    }
                }
                if (count($errors) == 0) {
                    //no errors
                    $xoops->redirect('field.php', 2, sprintf(_PROFILE_AM_SAVEDSUCCESS, _PROFILE_AM_FIELDS));
                } else {
                    $xoops->redirect('field.php', 3, implode('<br />', $errors));
                }
            }
        }
        break;

    case "save":
        if (!$xoops->security()->check()) {
            $xoops->redirect('field.php', 3, implode(',', $xoops->security()->getErrors()));
        }
        $redirect_to_edit = false;
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        /*  @var $obj ProfileField */
        if ($id > 0) {
            $obj = $field_handler->get($id);
            if (!$obj->getVar('field_config') && !$obj->getVar('field_show')
                && !$obj->getVar('field_edit')
            ) { //If no configs exist
                $xoops->redirect('admin.php', 2, _PROFILE_AM_FIELDNOTCONFIGURABLE);
            }
        } else {
            $obj = $field_handler->create();
            $obj->setVar('field_name', $_REQUEST['field_name']);
            $obj->setVar('field_moduleid', $xoops->module->getVar('mid'));
            $obj->setVar('field_show', 1);
            $obj->setVar('field_edit', 1);
            $obj->setVar('field_config', 1);
            $redirect_to_edit = true;
        }
        $obj->setVar('field_title', $_REQUEST['field_title']);
        $obj->setVar('field_description', $_REQUEST['field_description']);
        if ($obj->getVar('field_config')) {
            $obj->setVar('field_type', $_REQUEST['field_type']);
            if (isset($_REQUEST['field_valuetype'])) {
                $obj->setVar('field_valuetype', $_REQUEST['field_valuetype']);
            }
            $options = $obj->getVar('field_options');

            if (isset($_REQUEST['removeOptions']) && is_array($_REQUEST['removeOptions'])) {
                foreach ($_REQUEST['removeOptions'] as $index) {
                    unset($options[$index]);
                }
                $redirect_to_edit = true;
            }

            if (!empty($_REQUEST['addOption'])) {
                foreach ($_REQUEST['addOption'] as $option) {
                    if (empty($option['value'])) {
                        continue;
                    }
                    $options[$option['key']] = $option['value'];
                    $redirect_to_edit = true;
                }
            }
            $obj->setVar('field_options', $options);
        }
        if ($obj->getVar('field_edit')) {
            $required = isset($_REQUEST['field_required']) ? $_REQUEST['field_required'] : 0;
            $obj->setVar('field_required', $required); //0 = no, 1 = yes
            if (isset($_REQUEST['field_maxlength'])) {
                $obj->setVar('field_maxlength', $_REQUEST['field_maxlength']);
            }
            if (isset($_REQUEST['field_default'])) {
                $field_default = $obj->getValueForSave($_REQUEST['field_default']);
                //Check for multiple selections
                if (is_array($field_default)) {
                    $obj->setVar('field_default', serialize($field_default));
                } else {
                    $obj->setVar('field_default', $field_default);
                }
            }
        }

        if ($obj->getVar('field_show')) {
            $obj->setVar('field_weight', $_REQUEST['field_weight']);
            $obj->setVar('cat_id', $_REQUEST['field_category']);
        }
        if (isset($_REQUEST['step_id'])) {
            $obj->setVar('step_id', $_REQUEST['step_id']);
        }

        if ($field_handler->insertFields($obj)) {
            $groupperm_handler = $xoops->getHandlerGroupperm();

            $perm_arr = array();
            if ($obj->getVar('field_show')) {
                $perm_arr[] = 'profile_show';
                $perm_arr[] = 'profile_visible';
            }
            if ($obj->getVar('field_edit')) {
                $perm_arr[] = 'profile_edit';
            }
            if ($obj->getVar('field_edit') || $obj->getVar('field_show')) {
                $perm_arr[] = 'profile_search';
            }
            if (count($perm_arr) > 0) {
                foreach ($perm_arr as $perm) {
                    $criteria = new CriteriaCompo(new Criteria('gperm_name', $perm));
                    $criteria->add(new Criteria('gperm_itemid', intval($obj->getVar('field_id'))));
                    $criteria->add(new Criteria('gperm_modid', intval($xoops->module->getVar('mid'))));
                    if (isset($_REQUEST[$perm]) && is_array($_REQUEST[$perm])) {
                        $perms = $groupperm_handler->getObjects($criteria);
                        if (count($perms) > 0) {
                            foreach (array_keys($perms) as $i) {
                                $groups[$perms[$i]->getVar('gperm_groupid')] = $perms[$i];
                            }
                        } else {
                            $groups = array();
                        }
                        foreach ($_REQUEST[$perm] as $groupid) {
                            $groupid = intval($groupid);
                            if (!isset($groups[$groupid])) {
                                $perm_obj = $groupperm_handler->create();
                                $perm_obj->setVar('gperm_name', $perm);
                                $perm_obj->setVar('gperm_itemid', intval($obj->getVar('field_id')));
                                $perm_obj->setVar('gperm_modid', $xoops->module->getVar('mid'));
                                $perm_obj->setVar('gperm_groupid', $groupid);
                                $groupperm_handler->insert($perm_obj);
                                unset($perm_obj);
                            }
                        }
                        $removed_groups = array_diff(array_keys($groups), $_REQUEST[$perm]);
                        if (count($removed_groups) > 0) {
                            $criteria->add(
                                new Criteria('gperm_groupid', "(" . implode(',', $removed_groups) . ")", "IN")
                            );
                            $groupperm_handler->deleteAll($criteria);
                        }
                        unset($groups);
                    } else {
                        $groupperm_handler->deleteAll($criteria);
                    }
                    unset($criteria);
                }
            }
            $url = $redirect_to_edit ? 'field.php?op=edit&amp;id=' . $obj->getVar('field_id') : 'field.php';
            $xoops->redirect($url, 3, sprintf(_PROFILE_AM_SAVEDSUCCESS, _PROFILE_AM_FIELD));
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        $form = $xoops->getModuleForm($obj, 'regstep');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case "delete":
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_FIELD, 'field.php?op=new', 'add');
        $admin_page->addItemButton(_PROFILE_AM_FIELD_LIST, 'field.php', 'application-view-detail');
        $admin_page->renderButton();
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $field_handler->get($id);
            if (isset($_POST["ok"]) && $_POST["ok"] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect("field.php", 3, implode(",", $xoops->security()->getErrors()));
                }
                if ($field_handler->deleteFields($obj)) {
                    $xoops->redirect("field.php", 2, sprintf(_PROFILE_AM_DELETEDSUCCESS, _PROFILE_AM_CATEGORY));
                } else {
                    echo $xoops->alert('error', $obj->getHtmlErrors());
                }
            } else {
                // Define Stylesheet
                $xoops->theme()->addStylesheet('modules/system/css/admin.css');
                $xoops->tpl()->assign('form', false);
                $xoops->confirm(
                    array("ok" => 1, "id" => $id, "op" => "delete"),
                    'field.php',
                    sprintf(_PROFILE_AM_RUSUREDEL, $obj->getVar('field_title')) . '<br />'
                );
            }
        } else {
            $xoops->redirect('field.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;
}
$xoops->footer();

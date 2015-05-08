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
$xoops->header('admin:profile/categorylist.tpl');
// Get category handler
$category_Handler = $xoops->getModuleHandler("category");

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('category.php');

switch ($op) {
    case "list":
    default:
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_CATEGORY, 'category.php?op=new', 'add');
        $admin_page->renderButton();
        $criteria = new CriteriaCompo();
        $criteria->setSort('cat_weight');
        $criteria->setOrder('ASC');
        $xoops->tpl()->assign('categories', $category_Handler->getObjects($criteria, true, false));
        $xoops->tpl()->assign('category', true);
        break;

    case "new":
        $admin_page->addItemButton(_PROFILE_AM_CATEGORY_LIST, 'category.php', 'application-view-detail');
        $admin_page->renderButton();
        $obj = $category_Handler->create();
        $form = $xoops->getModuleForm($obj, 'category');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case "edit":
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_CATEGORY, 'category.php?op=new', 'add');
        $admin_page->addItemButton(_PROFILE_AM_CATEGORY_LIST, 'category.php', 'application-view-detail');
        $admin_page->renderButton();
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $category_Handler->get($id);
            $form = $xoops->getModuleForm($obj, 'category');
            $xoops->tpl()->assign('form', $form->render());
        } else {
            $xoops->redirect('category.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;

    case "save":
        if (!$xoops->security()->check()) {
            $xoops->redirect('category.php', 3, implode(',', $xoops->security()->getErrors()));
        }
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $category_Handler->get($id);
        } else {
            $obj = $category_Handler->create();
        }
        $obj->setVar('cat_title', $_POST['cat_title']);
        $obj->setVar('cat_description', $_POST['cat_description']);
        $obj->setVar('cat_weight', $_POST['cat_weight']);
        if ($category_Handler->insert($obj)) {
            $xoops->redirect('category.php', 3, sprintf(_PROFILE_AM_SAVEDSUCCESS, _PROFILE_AM_CATEGORY));
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        $form = $xoops->getModuleForm($obj, 'category');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case "delete":
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_CATEGORY, 'category.php?op=new', 'add');
        $admin_page->addItemButton(_PROFILE_AM_CATEGORY_LIST, 'category.php', 'application-view-detail');
        $admin_page->renderButton();
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $category_Handler->get($id);
            if (isset($_POST["ok"]) && $_POST["ok"] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect("category.php", 3, implode(",", $xoops->security()->getErrors()));
                }
                if ($category_Handler->delete($obj)) {
                    $xoops->redirect("category.php", 2, sprintf(_PROFILE_AM_DELETEDSUCCESS, _PROFILE_AM_CATEGORY));
                } else {
                    echo $xoops->alert('error', $obj->getHtmlErrors());
                }
            } else {
                // Define Stylesheet
                $xoops->theme()->addStylesheet('modules/system/css/admin.css');
                $xoops->tpl()->assign('form', false);
                $xoops->confirm(
                    array("ok" => 1, "id" => $id, "op" => "delete"),
                    'category.php',
                    sprintf(_PROFILE_AM_RUSUREDEL, $obj->getVar('cat_title')) . '<br />'
                );
            }
        } else {
            $xoops->redirect('category.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;
}
$xoops->footer();

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
$xoops->header('admin:profile/steplist.tpl');
// Get handler
$regstep_Handler = $xoops->getModuleHandler("regstep");

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('step.php');

switch ($op) {
    case "list":
    default:
        // Add Scripts
        $xoops->theme()->addScript('media/xoops/xoops.js');
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_STEP, 'step.php?op=new', 'add');
        $admin_page->renderButton();
        $xoops->tpl()->assign('steps', $regstep_Handler->getObjects(null, true, false));
        $xoops->tpl()->assign('step', true);
        break;

    case "new":
        $admin_page->addItemButton(_PROFILE_AM_STEP_LIST, 'step.php', 'application-view-detail');
        $admin_page->renderButton();
        $obj = $regstep_Handler->create();
        $form = $xoops->getModuleForm($obj, 'regstep');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case "edit":
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_STEP, 'step.php?op=new', 'add');
        $admin_page->addItemButton(_PROFILE_AM_STEP_LIST, 'step.php', 'application-view-detail');
        $admin_page->renderButton();
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $regstep_Handler->get($id);
            $form = $xoops->getModuleForm($obj, 'regstep');
            $xoops->tpl()->assign('form', $form->render());
        } else {
            $xoops->redirect('step.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;

    case "save":
        if (!$xoops->security()->check()) {
            $xoops->redirect('step.php', 3, implode(',', $xoops->security()->getErrors()));
        }
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $regstep_Handler->get($id);
        } else {
            $obj = $regstep_Handler->create();
        }
        $obj->setVar('step_name', $_POST['step_name']);
        $obj->setVar('step_order', $_POST['step_order']);
        $obj->setVar('step_desc', $_POST['step_desc']);
        $obj->setVar('step_save', $_POST['step_save']);
        if ($regstep_Handler->insert($obj)) {
            $xoops->redirect('step.php', 3, sprintf(_PROFILE_AM_SAVEDSUCCESS, _PROFILE_AM_STEP));
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        $form = $xoops->getModuleForm($obj, 'regstep');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case "delete":
        $admin_page->addItemButton(XoopsLocale::A_ADD . ' ' . _PROFILE_AM_STEP, 'step.php?op=new', 'add');
        $admin_page->addItemButton(_PROFILE_AM_STEP_LIST, 'step.php', 'application-view-detail');
        $admin_page->renderButton();
        $id = $system->cleanVars($_REQUEST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $regstep_Handler->get($id);
            if (isset($_POST["ok"]) && $_POST["ok"] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect("step.php", 3, implode(",", $xoops->security()->getErrors()));
                }
                if ($regstep_Handler->deleteRegstep($obj)) {
                    $xoops->redirect("step.php", 2, sprintf(_PROFILE_AM_DELETEDSUCCESS, _PROFILE_AM_CATEGORY));
                } else {
                    echo $xoops->alert('error', $obj->getHtmlErrors());
                }
            } else {
                // Define Stylesheet
                $xoops->theme()->addStylesheet('modules/system/css/admin.css');
                $xoops->tpl()->assign('form', false);
                $xoops->confirm(
                    array("ok" => 1, "id" => $id, "op" => "delete"),
                    'step.php',
                    sprintf(_PROFILE_AM_RUSUREDEL, $obj->getVar('step_name')) . '<br />'
                );
            }
        } else {
            $xoops->redirect('step.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;

    case "step_update":
        $id = $system->cleanVars($_POST, 'id', 0, 'int');
        if ($id > 0) {
            $obj = $regstep_Handler->get($id);
            $old = $obj->getVar('step_save');
            $obj->setVar('step_save', !$old);
            if ($regstep_Handler->insert($obj)) {
                exit;
            }
            echo $obj->getHtmlErrors();
        }
        break;
}
$xoops->footer();

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Request;

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$helper = Menus::getInstance();

// Call Header & ...
$xoops->header('admin:menus/menus_admin_menu.tpl');
$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('admin_menu.php');
$xoops->theme()->addStylesheet('modules/system/css/admin.css');

// Get $_GET, $_POST, ...
$op = Request::getCmd('op', 'list');
$id = Request::getInt('id', 0);
$pid = Request::getInt('pid', 0);
$weight = Request::getInt('weight', 0);
$visible = Request::getInt('visible', 0);


$menus_handler = $helper->getHandlerMenus();
$criteria = new CriteriaCompo();
$criteria->setSort('title');
$criteria->setOrder('ASC');
$menus_list = $menus_handler->getList($criteria);

$indexAdmin = new \Xoops\Module\Admin();

if (empty($menus_list)) {
    $xoops->redirect('admin_menus.php', 1, _AM_MENUS_MSG_NOMENUS);
}

if (isset($_REQUEST['menu_id']) && in_array($_REQUEST['menu_id'], array_keys($menus_list))) {
    $menu_id = $_REQUEST['menu_id'];
    $menu_title = $menus_list[$menu_id];
} else {
    $keys = array_keys($menus_list);
    $menu_id = $keys[0];
    $menu_title = $menus_list[$menu_id];
}

$xoops->tpl()->assign('menu_id', $menu_id);
$xoops->tpl()->assign('menu_title', $menu_title);
$xoops->tpl()->assign('menus_list', $menus_list);

switch ($op) {
    case 'add':
        $admin_page->addItemButton(_AM_MENUS_LIST_MENUS, 'admin_menu.php', 'application-view-detail');
        $admin_page->renderButton();
        // Create form
        $obj = $helper->getHandlerMenu()->create();
        $obj->setVar('pid', $pid);
        $obj->setVar('mid', $menu_id);
        $form = $helper->getForm($obj, 'menus_menu');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'edit':
        $admin_page->addItemButton(_AM_MENUS_LIST_MENUS, 'admin_menu.php', 'application-view-detail');
        $admin_page->renderButton();
        // Create form
        $id = Request::getInt('id', 0);
        $obj = $helper->getHandlerMenu()->get($id);
        $form = $helper->getForm($obj, 'menus_menu');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('admin_menu.php', 3, implode('<br />', $xoops->security()->getErrors()));
        }

        $msg[] = _AM_MENUS_SAVE;

        $id = Request::getInt('id', 0);
        if (isset($id) && $id !=0) {
            $obj = $helper->getHandlerMenu()->get($id);
        } else {
            $obj = $helper->getHandlerMenu()->create();
        }

        $this_handler = $helper->getHandlerMenu();
        $criteria = new CriteriaCompo(new Criteria('mid', $_POST['mid']));
        $criteria->setSort('weight');
        $criteria->setOrder('DESC');
        $criteria->setLimit(1);
        $menus = $this_handler->getObjects($criteria);
        $weight = 1;
        if (isset($menus[0]) && is_object($menus[0])) {
            $weight = $menus[0]->getVar('weight') + 1;
        }

        if (!isset($_POST['hooks'])) {
            $_POST['hooks'] = array();
        }
        $obj->setVars($_POST);
        $obj->setVar('weight', $weight);

        if ($helper->getHandlerMenu()->insert($obj)) {
            $this_handler->update_weights($obj);
            $xoops->redirect('admin_menu.php?op=list&amp;menu_id=' . $obj->getVar('mid'), 2, implode('<br />', $msg));
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        $form = $helper->getForm($obj, 'menus_menu');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'del':
        $ok = Request::getInt('ok', 0);
        $obj = $helper->getHandlerMenu()->get($id);

        if ($ok == 1) {
            if (!$xoops->security()->check()) {
                $xoops->redirect('admin_menu.php', 3, implode(',', $xoops->security()->getErrors()));
            }
            if ($helper->getHandlerMenu()->delete($obj)) {
                $xoops->redirect('admin_menu.php?menu_id=' . $menu_id, 2, _AM_MENUS_MSG_SUCCESS);
            } else {
                echo $xoops->alert('error', $obj->getHtmlErrors());
            }
        } else {
            $xoops->confirm(
                array('ok' => 1, 'id' => $id, 'op' => 'del', 'menu_id' => $menu_id),
                $helper->url('admin/admin_menu.php'),
                _AM_MENUS_MSG_SUREDEL . '<br /><strong>' . $obj->getVar('title') . '</strong>'
            );
        }
        break;

    case 'move':
        $this_handler = Menus::getInstance()->getHandlerMenu();
        $obj = $this_handler->get($id);
        $obj->setVar('weight', $weight);
        $this_handler->insert($obj);
        $this_handler->update_weights($obj);
        $xoops->redirect('admin_menu.php?op=list&amp;menu_id=' . $obj->getVar('mid'), 2, _AM_MENUS_SAVE);
        break;

    case 'toggle':
        $visible = ($visible == 1) ? 0 : 1;
        $this_handler = Menus::getInstance()->getHandlerMenu();
        $obj = $this_handler->get($id);
        $obj->setVar('visible', $visible);
        $this_handler->insert($obj);
        $xoops->redirect('admin_menu.php?op=list&amp;menu_id=' . $obj->getVar('mid'), 2, _AM_MENUS_SAVE);
        break;

    case 'list':
    default:
        $admin_page->addItemButton(_AM_MENUS_ADD_MENUS, 'admin_menu.php?op=add&amp;menu_id=' . $menu_id, 'add');
        $admin_page->renderButton();

        $this_handler = $helper->getHandlerMenu();

        $criteria = new CriteriaCompo(new Criteria('mid', $menu_id));
        $count = $this_handler->getCount($criteria);
        $xoops->tpl()->assign('count', $count);
        $xoops->tpl()->assign('select', true);
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');

        if ($count > 0) {
            $array = array();
            $menus = $this_handler->getObjects($criteria);
            /* @var $menu MenusMenu */
            foreach ($menus as $menu) {
                $array[] = $menu->getValues();
            }
            $builder = new MenusBuilder($array);
            $menusArray = $builder->render();
            $xoops->tpl()->assign('menus', $menusArray);
        } else {
             $xoops->tpl()->assign('error_message', _AM_MENUS_MSG_NOTFOUND);
        }
        break;
}
$xoops->footer();

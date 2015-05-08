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
$xoops->header('admin:menus/menus_admin_menus.tpl');
$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('admin_menus.php');
$xoops->theme()->addStylesheet('modules/system/css/admin.css');

// Get $_GET, $_POST, ...
$op =Request::getCmd('op', 'list');
$id = Request::getInt('id', 0);
$limit = Request::getInt('limit', 15);
$start = Request::getInt('start', 0);


switch ($op) {
    case 'add':
        $admin_page->addItemButton(_AM_MENUS_LIST_MENUS, 'admin_menus.php', 'application-view-detail');
        $admin_page->renderButton();
        // Create form
        $obj = $helper->getHandlerMenus()->create();
        $form = $helper->getForm($obj, 'menus_menus');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'edit':
        $admin_page->addItemButton(_AM_MENUS_LIST_MENUS, 'admin_menus.php', 'application-view-detail');
        $admin_page->renderButton();
        // Create form
        $id = Request::getInt('id', 0);
        $obj = $helper->getHandlerMenus()->get($id);
        $form = $helper->getForm($obj, 'menus_menus');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('admin_menus.php', 3, implode('<br />', $xoops->security()->getErrors()));
        }

        $msg[] = _AM_MENUS_SAVE;

        $id = Request::getInt('id', 0);
        if (isset($id) && $id !=0) {
            $obj = $helper->getHandlerMenus()->get($id);
        } else {
            $obj = $helper->getHandlerMenus()->create();
        }

        $obj->setVar('title', Request::getString('title', ''));

        if ($helper->getHandlerMenus()->insert($obj)) {
            $xoops->redirect('admin_menus.php', 2, implode('<br />', $msg));
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        $form = $helper->getForm($obj, 'menus_menus');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'del':
        $ok = Request::getInt('ok', 0);
        $obj = $helper->getHandlerMenus()->get($id);

        if ($ok == 1) {
            if (!$xoops->security()->check()) {
                $xoops->redirect('admin_menus.php', 3, implode(',', $xoops->security()->getErrors()));
            }
            if ($helper->getHandlerMenus()->delete($obj)) {
                $this_handler = $helper->getHandlerMenu();
                $criteria = new Criteria('mid', $id);
                $this_handler->deleteAll($criteria);
                $xoops->redirect('admin_menus.php', 2, _AM_MENUS_MSG_SUCCESS);
            } else {
                echo $xoops->alert('error', $obj->getHtmlErrors());
            }
        } else {
            $xoops->confirm(
                array('ok' => 1, 'id' => $id, 'op' => 'del'),
                $helper->url('admin/admin_menus.php'),
                _AM_MENUS_MSG_SUREDEL . '<br /><strong>' . $obj->getVar('title') . '</strong>'
            );
        }
        break;

    case 'list':
    default:
        $myts = MyTextSanitizer::getInstance();
        $admin_page->addItemButton(_AM_MENUS_ADD_MENUS, 'admin_menus.php?op=add', 'add');
        $admin_page->renderButton();

        $this_handler = $helper->getHandlerMenus();

        $query = Request::getString('query', '');

        $xoops->tpl()->assign('query', $query);

        $criteria = new CriteriaCompo();
        if ($query != '') {
            $crit = new CriteriaCompo(new Criteria('title', $myts->addSlashes($query) . '%', 'LIKE'));
            $criteria->add($crit);
        }

        $count = $this_handler->getCount($criteria);
        $xoops->tpl()->assign('count', $count);

        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $criteria->setSort('id');
        $criteria->setOrder('ASC');

        if ($count > 0) {
            // Display Page Navigation
            if ($count > $limit) {
                $nav = new XoopsPageNav($count, $limit, $start, 'start', 'op=list');
                $xoops->tpl()->assign('nav_menu', $nav->renderNav(2));
            }

            $objs = $this_handler->getObjects($criteria);
            /* @var $obj MenusMenus */
            foreach ($objs as $obj) {
                $xoops->tpl()->append('objs', $obj->getValues());
            }
        } else {
            $xoops->tpl()->assign('error_message', _AM_MENUS_MSG_NOTFOUND);
        }
        break;
}
$xoops->footer();

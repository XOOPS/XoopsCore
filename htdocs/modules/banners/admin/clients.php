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
 * banners module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */
include __DIR__ . '/header.php';

// Get main instance
$xoops = Xoops::getInstance();
$helper = Banners::getInstance();

$xoops_upload_path = \XoopsBaseConfig::get('uploads-path');
$xoops_upload_url = \XoopsBaseConfig::get('uploads-url');
$xoops_url = \XoopsBaseConfig::get('url');

// Parameters
$nb_clients = $helper->getConfig('banners_clientspager');
// Get banners handler
$banner_Handler = $helper->getHandlerBanner();
$client_Handler = $helper->getHandlerBannerclient();
// Get member handler
$member_handler = $xoops->getHandlerMember();
// Call header
$xoops->header('admin:banners/banners_admin_clients.tpl');
// Get Action type
$op = Request::getCmd('op', 'list');
// Get start pager
$start = Request::getInt('start', 0);

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('clients.php');

switch ($op) {

    case 'list':
    default:

        $admin_page->addTips(_AM_BANNERS_TIPS_CLIENTS);
        $admin_page->addItemButton(_AM_BANNERS_CLIENTS_ADD, 'clients.php?op=new', 'add');
        $admin_page->renderTips();
        $admin_page->renderButton();

        // Display client
        $criteria = new CriteriaCompo();
        $criteria->setSort("bannerclient_name");
        $criteria->setOrder("ASC");
        $criteria->setStart($start);
        $criteria->setLimit($nb_clients);

        $client_count = $client_Handler->getCount($criteria);
        $client_arr = $client_Handler->getAll($criteria);

        $xoops->tpl()->assign('client_count', $client_count);

        if ($client_count > 0) {
            foreach (array_keys($client_arr) as $i) {
                $criteria = new CriteriaCompo();
                $criteria->add(new Criteria('banner_cid', $client_arr[$i]->getVar("bannerclient_cid"), '='));
                $banner_active = $banner_Handler->getCount($criteria);
                $client['cid'] = $client_arr[$i]->getVar("bannerclient_cid");
                $client['uid'] = $client_arr[$i]->getVar("bannerclient_uid");
                $client['banner_active'] = $banner_active;
                if ($client_arr[$i]->getVar("bannerclient_uid") == 0) {
                    $client['uname'] = '/';
                    $client['email'] = '/';
                } else {
                    $user = $member_handler->getUser($client_arr[$i]->getVar("bannerclient_uid"));
                    $client['uname'] = $user->getVar("uname");
                    $client['email'] = $user->getVar("email");
                    $response = $xoops->service("Avatar")->getAvatarUrl($user);
                    $avatar = $response->getValue();
                    $avatar = empty($avatar) ? '' : $avatar;
                    $client['avatar'] = $avatar;
                    $client['url'] = $user->getVar("bannerclient_url");
                }
                $client['name'] = $client_arr[$i]->getVar("bannerclient_name");
                $client['extrainfo'] = $client_arr[$i]->getVar("bannerclient_extrainfo");
                $xoops->tpl()->appendByRef('client', $client);
                $xoops->tpl()->appendByRef('client_banner', $client);
                unset($client);
            }
        }
        // Display Page Navigation
        if ($client_count > $nb_clients) {
            $nav = new XoopsPageNav($client_count, $nb_clients, $start, 'start');
            $xoops->tpl()->assign('nav_menu', $nav->renderNav(4));
        }
        break;

    case 'new':
        $admin_page->addItemButton(_AM_BANNERS_CLIENTS_LIST, 'clients.php', 'application-view-detail');
        $admin_page->renderButton();
        $xoops->tpl()->assign(
            'info_msg',
            $xoops->alert('info', _AM_BANNERS_ALERT_INFO_CLIENT_ADDEDIT, _AM_BANNERS_ALERT_INFO_TITLE)
        );
        $obj = $client_Handler->create();
        $form = $helper->getForm($obj, 'bannerclient');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'edit':
        $admin_page->addItemButton(_AM_BANNERS_CLIENTS_LIST, 'clients.php', 'application-view-detail');
        $admin_page->renderButton();
        $xoops->tpl()->assign(
            'info_msg',
            $xoops->alert('info', _AM_BANNERS_ALERT_INFO_CLIENT_ADDEDIT, _AM_BANNERS_ALERT_INFO_TITLE)
        );
        $cid = Request::getInt('cid', 0);
        if ($cid > 0) {
            $obj = $client_Handler->get($cid);
            $form = $helper->getForm($obj, 'bannerclient');
            $xoops->tpl()->assign('form', $form->render());
        } else {
            $xoops->redirect('clients.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;

    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect("clients.php", 3, implode(",", $xoops->security()->getErrors()));
        }
        $cid = Request::getInt('cid', 0);
        if ($cid > 0) {
            $obj = $client_Handler->get($cid);
        } else {
            $obj = $client_Handler->create();
        }
        $obj->setVar("bannerclient_name", Request::getString('name', ''));
        if ($_POST["user"] == 'Y') {
            $obj->setVar("bannerclient_uid", Request::getInt('uid', 0));
        } else {
            $obj->setVar("bannerclient_uid", 0);
        }
        $obj->setVar("bannerclient_extrainfo", Request::getString('extrainfo', ''));
        if ($client_Handler->insert($obj)) {
            $xoops->redirect("clients.php", 2, _AM_BANNERS_DBUPDATED);
        }
        $xoops->tpl()->assign('error_msg', $xoops->alert('error', $obj->getHtmlErrors()));
        $form = $helper->getForm($obj, 'bannerclient');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'delete':
        $cid = Request::getInt('cid', 0);
        if ($cid > 0) {
            $obj = $client_Handler->get($cid);
            if (isset($_POST["ok"]) && $_POST["ok"] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect("clients.php", 3, implode(",", $xoops->security()->getErrors()));
                }
                if ($client_Handler->delete($obj)) {
                    // Delete client banners
                    $banner_arr = $banner_Handler->getAll(new Criteria('banner_cid', $cid));
                    foreach (array_keys($banner_arr) as $i) {
                        $obj = $banner_Handler->get($banner_arr[$i]->getVar('banner_bid'));
                        $namefile = substr_replace(
                            $banner_arr[$i]->getVar('banner_imageurl'),
                            '',
                            0,
                            strlen($xoops_url . '/uploads/banners/')
                        );
                        $urlfile =  $xoops_root_path . '/uploads/banners/' . $namefile;
                        if ($banner_Handler->delete($obj)) {
                            // delete banner
                            if (is_file($urlfile)) {
                                chmod($urlfile, 0777);
                                unlink($urlfile);
                            }
                        } else {
                            echo $xoops->alert('error', $obj->getHtmlErrors());
                        }
                    }
                    $xoops->redirect("clients.php", 2, _AM_BANNERS_DBUPDATED);
                } else {
                    echo $xoops->alert('error', $obj->getHtmlErrors());
                }
            } else {
                echo $xoops->confirm(
                    array("ok" => 1, "cid" => $cid, "op" => "delete"),
                    'clients.php',
                    sprintf(_AM_BANNERS_CLIENTS_SUREDEL, $obj->getVar("bannerclient_name")) . '<br />'
                );
            }
        } else {
            $xoops->redirect('clients.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;
}
$xoops->footer();

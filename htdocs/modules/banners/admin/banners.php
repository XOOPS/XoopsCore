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
 * banners module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         banners
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id: $
 */
include dirname(__FILE__) . '/header.php';
// Get main instance
$xoops = Xoops::getInstance();
$helper = Banners::getInstance();
$request = $xoops->request();
// Parameters
$nb_banners = $helper->getConfig('banners_pager');
$mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'application/x-shockwave-flash');
$upload_size = 500000;
// Get Action type
$op = $request->asStr('op', 'list');
// Get handler
$banner_Handler = $helper->getHandlerBanner();
$client_Handler = $helper->getHandlerBannerclient();
// Call header
$xoops->header('banners_admin_banners.html');

// Get start pager
$start = $request->asInt('start', 0);
$startF = $request->asInt('startF', 0);

$admin_page = new XoopsModuleAdmin();
$admin_page->renderNavigation('banners.php');

$info_msg = array(sprintf(_AM_BANNERS_ALERT_INFO_MIMETYPES , implode(", ", $mimetypes)), sprintf(_AM_BANNERS_ALERT_INFO_MAXFILE , $upload_size / 1000));

switch ($op) {

    case 'list':
    default:
        // Define Stylesheet
        $xoops->theme()->addStylesheet('media/jquery/ui/' . $xoops->getModuleConfig('jquery_theme', 'system') . '/ui.all.css');
        // Define scripts
        $xoops->theme()->addScript($xoops->url('/media/jquery/ui/jquery.ui.js'));
        $xoops->theme()->addScript('modules/system/js/admin.js');

        $admin_page->addTips(_AM_BANNERS_TIPS_BANNERS);
        $admin_page->addItemButton(_AM_BANNERS_BANNERS_ADD, 'banners.php?op=new', 'add');
        $admin_page->renderTips();
        if ($client_Handler->getCount() == 0){
            echo $xoops->alert('error', _AM_BANNERS_BANNERS_ERROR_NOCLIENT);
        } else {
            $admin_page->renderButton();
        }
        // Display banner
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('banner_status', 0, '!='));
        $criteria->setSort("banner_datestart");
        $criteria->setOrder("DESC");
        $criteria->setStart($start);
        $criteria->setLimit($nb_banners);

        $banner_count = $banner_Handler->getCount($criteria);
        $banner_arr = $banner_Handler->getall($criteria);

        $xoops->tpl()->assign('banner_count', $banner_count);

        if ($banner_count > 0) {
            foreach (array_keys($banner_arr) as $i) {
                $imptotal = $banner_arr[$i]->getVar("banner_imptotal");
                $impmade = $banner_arr[$i]->getVar("banner_impmade");
                $imageurl = $banner_arr[$i]->getVar("banner_imageurl");
                $clicks = $banner_arr[$i]->getVar("banner_clicks");
                $htmlbanner = $banner_arr[$i]->getVar("banner_htmlbanner");
                $htmlcode = $banner_arr[$i]->getVar("banner_htmlcode");
                $name_client = $client_Handler->get($banner_arr[$i]->getVar("banner_cid"));
                $name = '';
                if (is_object($name_client)) {
                    $name = $name_client->getVar("bannerclient_name");
                }

                if ($impmade == 0) {
                    $percent = 0;
                } else {
                    $percent = substr(100 * $clicks / $impmade, 0, 5);
                }
                if ($imptotal == 0) {
                    $left = "" . _AM_BANNERS_BANNERS_UNLIMIT . "";
                } else {
                    $left = $imptotal - $impmade;
                }
                $img = '';
                if ($htmlbanner) {
                    $img .= html_entity_decode($htmlcode);
                } else {
                    if (stristr($imageurl, '.swf')) {
                        $img .= '<object type="application/x-shockwave-flash" width="468" height="60" data="' . $imageurl . '" style="z-index:100;">' . '<param name="movie" value="' . $imageurl . '" />' . '<param name="wmode" value="opaque" />' . '</object>';
                    } else {
                        $img .= '<img src="' . $imageurl . '" alt="" />';
                    }
                }

                $banner['bid'] = $banner_arr[$i]->getVar("banner_bid");
                $banner['impmade'] = $impmade;
                $banner['clicks'] = $clicks;
                $banner['left'] = $left;
                $banner['percent'] = $percent;
                $banner['imageurl'] = $img;
                $banner['name'] = $name;
                $xoops->tpl()->append_by_ref('banner', $banner);
                $xoops->tpl()->append_by_ref('popup_banner', $banner);
                unset($banner);
            }
        }
        // Display Page Navigation
        if ($banner_count > $nb_banners) {
            $nav = new XoopsPageNav($banner_count, $nb_banners, $start, 'start', 'startF=' . $startF);
            $xoops->tpl()->assign('nav_menu_banner', $nav->renderNav(4));
        }
        // Display Finished Banners
        // Criteria
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('banner_status', 0));
        $criteria->setSort("banner_datestart");
        $criteria->setOrder("DESC");
        $criteria->setStart($startF);
        $criteria->setLimit($nb_banners);

        $banner_finish_count = $banner_Handler->getCount($criteria);
        $banner_finish_arr = $banner_Handler->getall($criteria);

        $xoops->tpl()->assign('banner_finish_count', $banner_finish_count);

        if ($banner_finish_count > 0) {
            foreach (array_keys($banner_finish_arr) as $i) {
                $bid = $banner_finish_arr[$i]->getVar("banner_bid");
                $imageurl = $banner_finish_arr[$i]->getVar("banner_imageurl");
                $htmlbanner = $banner_finish_arr[$i]->getVar("banner_htmlbanner");
                $htmlcode = $banner_finish_arr[$i]->getVar("banner_htmlcode");
                $impressions = $banner_finish_arr[$i]->getVar("banner_impmade");
                $clicks = $banner_finish_arr[$i]->getVar("banner_clicks");
                if ($impressions != 0) {
                    $percent = substr(100 * $clicks / $impressions, 0, 5);
                } else {
                    $percent = 0;
                }
                $img = '';
                if ($htmlbanner) {
                    $img .= html_entity_decode($htmlcode);
                } else {
                    $img = '<div id="xo-bannerfix">';
                    if (stristr($imageurl, '.swf')) {
                        $img .= '<object type="application/x-shockwave-flash" width="468" height="60" data="' . $imageurl . '" style="z-index:100;">' . '<param name="movie" value="' . $imageurl . '" />' . '<param name="wmode" value="opaque" />' . '</object>';
                    } else {
                        $img .= '<img src="' . $imageurl . '" alt="" />';
                    }

                    $img .= '</div>';
                }
                $banner_finish['bid'] = $bid;
                $banner_finish['impressions'] = $impressions;
                $banner_finish['clicks'] = $clicks;
                $banner_finish['percent'] = $percent;
                $banner_finish['imageurl'] = $img;
                $banner_finish['datestart'] = XoopsLocale::formatTimestamp($banner_finish_arr[$i]->getVar("banner_datestart"), "m");
                $banner_finish['dateend'] = XoopsLocale::formatTimestamp($banner_finish_arr[$i]->getVar("banner_dateend"), "m");
                $name_client = $client_Handler->get($banner_finish_arr[$i]->getVar("banner_cid"));
                $name = '';
                if (is_object($name_client)) {
                    $name = $name_client->getVar("bannerclient_name");
                }
                $banner_finish['name'] = $name;
                $xoops->tpl()->append_by_ref('banner_finish', $banner_finish);
                $xoops->tpl()->append_by_ref('popup_banner_finish', $banner_finish);
                unset($banner_finish);
            }
        }
        // Display Page Navigation
        if ($banner_finish_count > $nb_banners) {
            $nav = new XoopsPageNav($banner_finish_count, $nb_banners, $startF, 'startF', 'start=' . $start);
            $xoops->tpl()->assign('nav_menu_bannerF', $nav->renderNav(4));
        }
        break;

    case 'new':
        $admin_page->addItemButton(_AM_BANNERS_BANNERS_LIST, 'banners.php', 'application-view-detail');
        $admin_page->renderButton();
        $xoops->tpl()->assign('info_msg', $xoops->alert('info', $info_msg, _AM_BANNERS_ALERT_INFO_TITLE_UPLOADS));
        $obj = $banner_Handler->create();
        $form = $helper->getForm($obj, 'banner');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'edit':
        $admin_page->addItemButton(_AM_BANNERS_BANNERS_LIST, 'banners.php', 'application-view-detail');
        $admin_page->renderButton();
        $xoops->tpl()->assign('info_msg', $xoops->alert('info', $info_msg, _AM_BANNERS_ALERT_INFO_TITLE_UPLOADS));
        $bid = $request->asInt('bid', 0);
        if ($bid > 0) {
            $obj = $banner_Handler->get($bid);
            $form = $helper->getForm($obj, 'banner');
            $xoops->tpl()->assign('form', $form->render());
        } else {
            $xoops->redirect('banners.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;

    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect("banners.php", 3, implode(",", $xoops->security()->getErrors()));
        }
        $bid = $request->asInt('bid', 0);
        if ($bid > 0) {
            $obj = $banner_Handler->get($bid);
        } else {
            $obj = $banner_Handler->create();
            $obj->setVar("banner_datestart", time());
            $obj->setVar("banner_dateend", 0);
            $obj->setVar("banner_status", 1);
        }
        $error_msg = '';
        $obj->setVar("banner_cid", $request->asInt('cid', 0));
        if (preg_match('/^[0-9]*[0-9]+$|^[0-9]+[0-9]*$/', $_POST["imptotal"]) == false){
            $error_msg .= XoopsLocale::E_YOU_NEED_A_POSITIVE_INTEGER . '<br />';
            $obj->setVar("banner_imptotal", 0);
        } else {
            $obj->setVar("banner_imptotal", $request->asInt('imptotal', 0));
        }
        $obj->setVar("banner_clickurl", $request->asStr('clickurl', ''));
        $obj->setVar("banner_htmlbanner", $request->asInt('htmlbanner', 0));
        $obj->setVar("banner_htmlcode", $request->asStr('htmlcode', ''));

        $uploader_banners_img = new XoopsMediaUploader(XOOPS_UPLOAD_PATH . '/banners', $mimetypes, $upload_size, null, null);

        if ($uploader_banners_img->fetchMedia("banners_imageurl")) {
            $uploader_banners_img->setPrefix("banner");
            $uploader_banners_img->fetchMedia("banners_imageurl");
            if (!$uploader_banners_img->upload()) {
                $error_msg .= $uploader_banners_img->getErrors();
            } else {
                $obj->setVar("banner_imageurl", XOOPS_UPLOAD_URL . '/banners/' . $uploader_banners_img->getSavedFileName());
            }
        } else {
            if ($_POST["banners_imageurl"] == 'blank.gif') {
                $obj->setVar("banner_imageurl", $request->asStr('imageurl', ''));
            } else {
                $obj->setVar("banner_imageurl", XOOPS_UPLOAD_URL . '/banners/' . $request->asStr('banners_imageurl', ''));
            }
        }

        if ($error_msg == ''){
            if ($banner_Handler->insert($obj)) {
                $xoops->redirect("banners.php", 2, XoopsLocale::S_ITEM_SAVED);
            }
            $error_msg .= $obj->getHtmlErrors();
        }
        $admin_page->addItemButton(_AM_BANNERS_BANNERS_LIST, 'banners.php', 'application-view-detail');
        $admin_page->renderButton();
        $xoops->tpl()->assign('info_msg', $xoops->alert('info', $info_msg, _AM_BANNERS_ALERT_INFO_TITLE_UPLOADS));
        $xoops->tpl()->assign('error_msg', $xoops->alert('error', $error_msg));
        $form = $helper->getForm($obj, 'banner');
        $xoops->tpl()->assign('form', $form->render());
        break;

    case 'delete':
        $bid = $request->asInt('bid', 0);
        if ($bid > 0) {
            $obj = $banner_Handler->get($bid);
            if (isset($_POST["ok"]) && $_POST["ok"] == 1) {
                if (!$xoops->security()->check()) {
                    $xoops->redirect("banners.php", 3, implode(",", $xoops->security()->getErrors()));
                }
                $namefile = substr_replace($obj->getVar('imageurl'),'',0,strlen(XOOPS_URL . '/uploads/banners/'));
                $urlfile =  XOOPS_ROOT_PATH . '/uploads/banners/' . $namefile;
                if ($banner_Handler->delete($obj)) {
                    // delete banner
                    if (is_file($urlfile)){
                        chmod($urlfile, 0777);
                        unlink($urlfile);
                    }
                    $xoops->redirect("banners.php", 2, _AM_BANNERS_DBUPDATED);
                } else {
                    echo $xoops->alert('error', $obj->getHtmlErrors());
                }
            } else {
                $img = '';
                $imageurl = $obj->getVar("banner_imageurl");
                if ($obj->getVar("banner_htmlbanner")) {
                    $img .= html_entity_decode($obj->getVar("banner_htmlcode"));
                } else {
                    if (strtolower(substr($imageurl, strrpos($imageurl, "."))) == ".swf") {
                        $img .= "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/ swflash.cab#version=6,0,40,0\" width=\"468\" height=\"60\">";
                        $img .= "<param name=movie value=\"$imageurl\">";
                        $img .= "<embed src=\"$imageurl\" pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\"  type=\"application/x-shockwave-flash\" width=\"468\" height=\"60\">";
                        $img .= "</embed>";
                        $img .= "</object>";
                    } else {
                        $img .= "<img src='" . $imageurl . "' alt='' />";
                    }
                }
                $xoops->confirm(array("ok" => 1, "bid" => $bid, "op" => "delete"), 'banners.php', XoopsLocale::Q_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_ITEM . '<br \>' . $img . '<br \>');
            }
        } else {
            $xoops->redirect('banners.php', 1, XoopsLocale::E_DATABASE_NOT_UPDATED);
        }
        break;

    case 'reload':
        $bid = $request->asInt('bid', 0);
        $obj = $banner_Handler->get($bid);
        $obj->setVar("banner_datestart", time());
        $obj->setVar("banner_dateend", 0);
        $obj->setVar("banner_imptotal", 0);
        $obj->setVar("banner_impmade", 0);
        $obj->setVar("banner_clicks", 0);
        $obj->setVar("banner_status", 1);
        if ($banner_Handler->insert($obj)) {
            $xoops->redirect("banners.php", 2, _AM_BANNERS_DBUPDATED);
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        break;
}
$xoops->footer();
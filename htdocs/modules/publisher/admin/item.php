<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xmf\Request;
use Xoops\Core\XoopsTpl;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 */

include_once __DIR__ . '/admin_header.php';

$xoops = Xoops::getInstance();

$itemid = Request::getInt('itemid');
$op = ($itemid > 0 || isset($_POST['editor'])) ? 'mod' : '';
$op = Request::getCmd('op', $op);

if (isset($_POST['additem'])) {
    $op = 'additem';
} else {
    if (isset($_POST['del'])) {
        $op = 'del';
    }
}

// Where shall we start ?
$submittedstartitem = Request::getInt('submittedstartitem');
$publishedstartitem = Request::getInt('publishedstartitem');
$offlinestartitem = Request::getInt('offlinestartitem');
$rejectedstartitem = Request::getInt('rejectedstartitem');

switch ($op) {
    case "clone":
        if ($itemid == 0) {
            $totalcategories = $publisher->getCategoryHandler()->getCategoriesCount(-1);
            if ($totalcategories == 0) {
                $xoops->redirect("category.php?op=mod", 3, _AM_PUBLISHER_NEED_CATEGORY_ITEM);

            }
        }
        PublisherUtils::cpHeader();
        publisher_editItem(true, $itemid, true);
        break;

    case "mod":
        if ($itemid == 0) {
            $totalcategories = $publisher->getCategoryHandler()->getCategoriesCount(-1);
            if ($totalcategories == 0) {
                $xoops->redirect("category.php?op=mod", 3, _AM_PUBLISHER_NEED_CATEGORY_ITEM);
                exit();
            }
        }

        PublisherUtils::cpHeader();
        publisher_editItem(true, $itemid);
        break;

    case "additem":
        // Creating the item object
        /* @var $itemObj PublisherItem */
        if ($itemid != 0) {
            $itemObj = $publisher->getItemHandler()->get($itemid);
        } else {
            $itemObj = $publisher->getItemHandler()->create();
        }

        $itemObj->setVarsFromRequest();

        $old_status = $itemObj->getVar('status');
        $new_status = \Xmf\Request::getInt('status', _PUBLISHER_STATUS_PUBLISHED); //_PUBLISHER_STATUS_NOTSET;

        $error_msg = '';
        $redirect_msg = '';
        switch ($new_status) {
            case _PUBLISHER_STATUS_SUBMITTED:
                if (($old_status == _PUBLISHER_STATUS_NOTSET)) {
                    $error_msg = _AM_PUBLISHER_ITEMNOTUPDATED;
                } else {
                    $error_msg = _AM_PUBLISHER_ITEMNOTCREATED;
                }
                $redirect_msg = _AM_PUBLISHER_ITEM_RECEIVED_NEED_APPROVAL;
                break;

            case _PUBLISHER_STATUS_PUBLISHED:
                if (($old_status == _PUBLISHER_STATUS_NOTSET) || ($old_status == _PUBLISHER_STATUS_SUBMITTED)) {
                    $redirect_msg = _AM_PUBLISHER_SUBMITTED_APPROVE_SUCCESS;
                    $notifToDo = array(_PUBLISHER_NOT_ITEM_PUBLISHED);
                } else {
                    $redirect_msg = _AM_PUBLISHER_PUBLISHED_MOD_SUCCESS;
                }
                $error_msg = _AM_PUBLISHER_ITEMNOTUPDATED;
                break;

            case _PUBLISHER_STATUS_OFFLINE:
                if ($old_status == _PUBLISHER_STATUS_NOTSET) {
                    $redirect_msg = _AM_PUBLISHER_OFFLINE_CREATED_SUCCESS;
                } else {
                    $redirect_msg = _AM_PUBLISHER_OFFLINE_MOD_SUCCESS;
                }
                $error_msg = _AM_PUBLISHER_ITEMNOTUPDATED;
                break;

            case _PUBLISHER_STATUS_REJECTED:
                if ($old_status == _PUBLISHER_STATUS_NOTSET) {
                    $error_msg = _AM_PUBLISHER_ITEMNOTUPDATED;
                } else {
                    $error_msg = _AM_PUBLISHER_ITEMNOTCREATED;
                }
                $redirect_msg = _AM_PUBLISHER_ITEM_REJECTED;
                break;
        }
        $itemObj->setVar('status', $new_status);

        // Storing the item
        if (!$itemObj->store()) {
            $xoops->redirect("javascript:history.go(-1)", 3, $error_msg . PublisherUtils::formatErrors($itemObj->getErrors()));
        }

        // attach file if any
        if (isset($_FILES['item_upload_file']) && $_FILES['item_upload_file']['name'] != "") {
            $file_upload_result = PublisherUtils::uploadFile(false, false, $itemObj);
            if ($file_upload_result !== true) {
                $xoops->redirect("javascript:history.go(-1)", 3, $file_upload_result);
                exit;
            }
        }

        // Send notifications
        if (!empty($notifToDo)) {
            $itemObj->sendNotifications($notifToDo);
        }
        $xoops->redirect("item.php", 2, $redirect_msg);
        break;

    case "del":
        /* @var $itemObj PublisherItem */
        $itemObj = $publisher->getItemHandler()->get($itemid);
        $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;

        if ($confirm) {
            if (!$publisher->getItemHandler()->delete($itemObj)) {
                $xoops->redirect("item.php", 2, _AM_PUBLISHER_ITEM_DELETE_ERROR . PublisherUtils::formatErrors($itemObj->getErrors()));
                exit();
            }
            $xoops->redirect("item.php", 2, sprintf(_AM_PUBLISHER_ITEMISDELETED, $itemObj->title()));
        } else {
            $xoops->header();
            echo $xoops->confirm(array(
                'op' => 'del', 'itemid' => $itemObj->getVar('itemid'), 'confirm' => 1, 'name' => $itemObj->title()
            ), 'item.php', _AM_PUBLISHER_DELETETHISITEM . " <br />'" . $itemObj->title() . "'. <br /> <br />", _AM_PUBLISHER_DELETE);
            $xoops->footer();
        }
        exit();
        break;

    case "default":
    default:
        PublisherUtils::cpHeader();
        //publisher_adminMenu(2, _AM_PUBLISHER_ITEMS);

        echo "<br />\n";
        echo "<form><div style=\"margin-bottom: 12px;\">";
        echo "<input type='button' name='button' onclick=\"location='item.php?op=mod'\" value='" . _AM_PUBLISHER_CREATEITEM . "'>&nbsp;&nbsp;";
        echo "</div></form>";

        $orderBy = 'datesub';
        $ascOrDesc = 'DESC';

        // Display Submited articles
        PublisherUtils::openCollapsableBar('submiteditemstable', 'submiteditemsicon', _AM_PUBLISHER_SUBMISSIONSMNGMT, _AM_PUBLISHER_SUBMITTED_EXP);

        // Get the total number of submitted ITEM
        $totalitems = $publisher->getItemHandler()->getItemsCount(-1, array(_PUBLISHER_STATUS_SUBMITTED));

        $itemsObj = $publisher->getItemHandler()
                ->getAllSubmitted($publisher->getConfig('idxcat_perpage'), $submittedstartitem, -1, $orderBy, $ascOrDesc);

        $totalItemsOnPage = count($itemsObj);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . "</strong></td>";
        echo "<td width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . "</strong></td>";
        echo "<td class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . "</strong></td>";
        echo "<td width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . "</strong></td>";
        echo "<td width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . "</strong></td>";
        echo "</tr>";
        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->category();

                $approve = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/approve.gif' title='" . _AM_PUBLISHER_SUBMISSION_MODERATE . "' alt='" . _AM_PUBLISHER_SUBMISSION_MODERATE . "' /></a>&nbsp;";
                $clone = '';
                $delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/delete.png' title='" . _AM_PUBLISHER_DELETEITEM . "' alt='" . _AM_PUBLISHER_DELETEITEM . "' /></a>";
                $modify = "";

                echo "<tr>";
                echo "<td class='head' align='center'>" . $itemsObj[$i]->getVar('itemid') . "</td>";
                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . "</td>";
                echo "<td class='even' align='left'><a href='" . PUBLISHER_URL . "/item.php?itemid=" . $itemsObj[$i]->getVar('itemid') . "'>" . $itemsObj[$i]->title() . "</a></td>";
                echo "<td class='even' align='center'>" . $itemsObj[$i]->datesub() . "</td>";
                echo "<td class='even' align='center'> $approve $clone $modify $delete </td>";
                echo "</tr>";
            }
        } else {
            $itemid = 0;
            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS_SUBMITTED . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        echo "<br />\n";

        $pagenav = new XoopsPageNav($totalitems, $publisher->getConfig('idxcat_perpage'), $submittedstartitem, 'submittedstartitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        PublisherUtils::closeCollapsableBar('submiteditemstable', 'submiteditemsicon');

        // Display Published articles
        PublisherUtils::openCollapsableBar('item_publisheditemstable', 'item_publisheditemsicon', _AM_PUBLISHER_PUBLISHEDITEMS, _AM_PUBLISHER_PUBLISHED_DSC);

        // Get the total number of published ITEM
        $totalitems = $publisher->getItemHandler()->getItemsCount(-1, array(_PUBLISHER_STATUS_PUBLISHED));

        $itemsObj = $publisher->getItemHandler()
                ->getAllPublished($publisher->getConfig('idxcat_perpage'), $publishedstartitem, -1, $orderBy, $ascOrDesc);

        $totalItemsOnPage = count($itemsObj);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . "</strong></td>";
        echo "<td width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . "</strong></td>";
        echo "<td class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . "</strong></td>";
        echo "<td width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . "</strong></td>";
        echo "<td width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . "</strong></td>";
        echo "</tr>";
        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->category();

                $modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/edit.gif' title='" . _AM_PUBLISHER_EDITITEM . "' alt='" . _AM_PUBLISHER_EDITITEM . "' /></a>";
                $delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/delete.png' title='" . _AM_PUBLISHER_DELETEITEM . "' alt='" . _AM_PUBLISHER_DELETEITEM . "'/></a>";
                $clone = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/clone.gif' title='" . _AM_PUBLISHER_CLONE_ITEM . "' alt='" . _AM_PUBLISHER_CLONE_ITEM . "' /></a>";

                echo "<tr>";
                echo "<td class='head' align='center'>" . $itemsObj[$i]->getVar('itemid') . "</td>";
                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . "</td>";
                echo "<td class='even' align='left'>" . $itemsObj[$i]->getItemLink() . "</td>";
                echo "<td class='even' align='center'>" . $itemsObj[$i]->datesub() . "</td>";
                echo "<td class='even' align='center'> $clone $modify $delete </td>";
                echo "</tr>";
            }
        } else {
            $itemid = 0;
            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        echo "<br />\n";

        $pagenav = new XoopsPageNav($totalitems, $publisher->getConfig('idxcat_perpage'), $publishedstartitem, 'publishedstartitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        PublisherUtils::closeCollapsableBar('item_publisheditemstable', 'item_publisheditemsicon');

        // Display Offline articles
        PublisherUtils::openCollapsableBar('offlineitemstable', 'offlineitemsicon', _AM_PUBLISHER_ITEMS . " " . _CO_PUBLISHER_OFFLINE, _AM_PUBLISHER_OFFLINE_EXP);

        $totalitems = $publisher->getItemHandler()->getItemsCount(-1, array(_PUBLISHER_STATUS_OFFLINE));

        $itemsObj = $publisher->getItemHandler()
                ->getAllOffline($publisher->getConfig('idxcat_perpage'), $offlinestartitem, -1, $orderBy, $ascOrDesc);

        $totalItemsOnPage = count($itemsObj);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . "</strong></td>";
        echo "<td width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . "</strong></td>";
        echo "<td class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . "</strong></td>";
        echo "<td width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . "</strong></td>";
        echo "<td width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . "</strong></td>";
        echo "</tr>";
        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->category();

                $modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/edit.gif' title='" . _AM_PUBLISHER_EDITITEM . "' alt='" . _AM_PUBLISHER_EDITITEM . "' /></a>";
                $delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/delete.png' title='" . _AM_PUBLISHER_DELETEITEM . "' alt='" . _AM_PUBLISHER_DELETEITEM . "'/></a>";
                $clone = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/clone.gif' title='" . _AM_PUBLISHER_CLONE_ITEM . "' alt='" . _AM_PUBLISHER_CLONE_ITEM . "' /></a>";

                echo "<tr>";
                echo "<td class='head' align='center'>" . $itemsObj[$i]->getVar('itemid') . "</td>";
                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . "</td>";
                echo "<td class='even' align='left'>" . $itemsObj[$i]->getItemLink() . "</td>";
                echo "<td class='even' align='center'>" . $itemsObj[$i]->datesub() . "</td>";
                echo "<td class='even' align='center'> $clone $modify $delete </td>";
                echo "</tr>";
            }
        } else {
            $itemid = 0;
            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS_OFFLINE . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        echo "<br />\n";

        $pagenav = new XoopsPageNav($totalitems, $publisher->getConfig('idxcat_perpage'), $offlinestartitem, 'offlinestartitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        PublisherUtils::closeCollapsableBar('offlineitemstable', 'offlineitemsicon');

        // Display Rejected articles
        PublisherUtils::openCollapsableBar('Rejecteditemstable', 'rejecteditemsicon', _AM_PUBLISHER_REJECTED_ITEM, _AM_PUBLISHER_REJECTED_ITEM_EXP, _AM_PUBLISHER_SUBMITTED_EXP);

        // Get the total number of Rejected ITEM
        $totalitems = $publisher->getItemHandler()->getItemsCount(-1, array(_PUBLISHER_STATUS_REJECTED));

        $itemsObj = $publisher->getItemHandler()
                ->getAllRejected($publisher->getConfig('idxcat_perpage'), $rejectedstartitem, -1, $orderBy, $ascOrDesc);

        $totalItemsOnPage = count($itemsObj);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td width='40' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ITEMID . "</strong></td>";
        echo "<td width='20%' class='bg3' align='left'><strong>" . _AM_PUBLISHER_ITEMCATEGORYNAME . "</strong></td>";
        echo "<td class='bg3' align='left'><strong>" . _AM_PUBLISHER_TITLE . "</strong></td>";
        echo "<td width='90' class='bg3' align='center'><strong>" . _AM_PUBLISHER_CREATED . "</strong></td>";
        echo "<td width='80' class='bg3' align='center'><strong>" . _AM_PUBLISHER_ACTION . "</strong></td>";
        echo "</tr>";
        if ($totalitems > 0) {
            for ($i = 0; $i < $totalItemsOnPage; ++$i) {
                $categoryObj = $itemsObj[$i]->category();

                $modify = "<a href='item.php?op=mod&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/edit.gif' title='" . _AM_PUBLISHER_EDITITEM . "' alt='" . _AM_PUBLISHER_EDITITEM . "' /></a>";
                $delete = "<a href='item.php?op=del&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/delete.png' title='" . _AM_PUBLISHER_DELETEITEM . "' alt='" . _AM_PUBLISHER_DELETEITEM . "'/></a>";
                $clone = "<a href='item.php?op=clone&itemid=" . $itemsObj[$i]->getVar('itemid') . "'><img src='" . PUBLISHER_URL . "/images/links/clone.gif' title='" . _AM_PUBLISHER_CLONE_ITEM . "' alt='" . _AM_PUBLISHER_CLONE_ITEM . "' /></a>";

                echo "<tr>";
                echo "<td class='head' align='center'>" . $itemsObj[$i]->getVar('itemid') . "</td>";
                echo "<td class='even' align='left'>" . $categoryObj->getCategoryLink() . "</td>";
                echo "<td class='even' align='left'>" . $itemsObj[$i]->getItemLink() . "</td>";
                echo "<td class='even' align='center'>" . $itemsObj[$i]->datesub() . "</td>";
                echo "<td class='even' align='center'> $clone $modify $delete </td>";
                echo "</tr>";
            }
        } else {
            $itemid = 0;
            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_PUBLISHER_NOITEMS_REJECTED . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        echo "<br />\n";

        $pagenav = new XoopsPageNav($totalitems, $publisher->getConfig('idxcat_perpage'), $rejectedstartitem, 'rejectedstartitem');
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

        PublisherUtils::closeCollapsableBar('Rejecteditemstable', 'rejecteditemsicon');
        break;
}
$xoops->footer();

function publisher_editItem($showmenu = false, $itemid = 0, $clone = false)
{
    $xoops = Xoops::getInstance();
    $publisher = Publisher::getInstance();
    global $publisher_current_page;

    $formTpl = new XoopsTpl();
    //publisher_submit.html

    // if there is a parameter, and the id exists, retrieve data: we're editing a item

    if ($itemid != 0) {

        // Creating the ITEM object
        /* @var $itemObj PublisherItem */
        $itemObj = $publisher->getItemHandler()->get($itemid);

        if (!$itemObj) {
            $xoops->redirect("item.php", 1, _AM_PUBLISHER_NOITEMSELECTED);
        }

        if ($clone) {
            $itemObj->setNew();
            $itemObj->setVar('itemid', 0);
            $itemObj->setVar('status', _PUBLISHER_STATUS_NOTSET);
            $itemObj->setVar('datesub', time());
        }

        switch ($itemObj->getVar('status')) {

            case _PUBLISHER_STATUS_SUBMITTED:
                $page_title = _AM_PUBLISHER_SUBMITTED_TITLE;
                $page_info = _AM_PUBLISHER_SUBMITTED_INFO;
                break;

            case _PUBLISHER_STATUS_PUBLISHED:
                $page_title = _AM_PUBLISHER_PUBLISHEDEDITING;
                $page_info = _AM_PUBLISHER_PUBLISHEDEDITING_INFO;
                break;

            case _PUBLISHER_STATUS_OFFLINE:
                $page_title = _AM_PUBLISHER_OFFLINEEDITING;
                $page_info = _AM_PUBLISHER_OFFLINEEDITING_INFO;
                break;

            case _PUBLISHER_STATUS_REJECTED:
                $page_title = _AM_PUBLISHER_REJECTED_EDIT;
                $page_info = _AM_PUBLISHER_REJECTED_EDIT_INFO;
                break;

            case _PUBLISHER_STATUS_NOTSET: // Then it's a clone...
                $page_title = _AM_PUBLISHER_ITEM_DUPLICATING;
                $page_info = _AM_PUBLISHER_ITEM_DUPLICATING_DSC;
                break;

            case "default":
            default:
                $page_title = _AM_PUBLISHER_PUBLISHEDEDITING;
                $page_info = _AM_PUBLISHER_PUBLISHEDEDITING_INFO;
                break;
        }

        echo "<br />\n";
        PublisherUtils::openCollapsableBar('edititemtable', 'edititemicon', $page_title, $page_info);

        if (!$clone) {
            echo "<form><div style=\"margin-bottom: 10px;\">";
            echo "<input type='button' name='button' onclick=\"location='item.php?op=clone&itemid=" . $itemObj->getVar('itemid') . "'\" value='" . _AM_PUBLISHER_CLONE_ITEM . "'>&nbsp;&nbsp;";
            echo "</div></form>";
        }
    } else {
        // there's no parameter, so we're adding an item

        /* @var $itemObj PublisherItem */
        $itemObj = $publisher->getItemHandler()->create();
        $itemObj->setVarsFromRequest();

        $categoryObj = $publisher->getCategoryHandler()->create();
        $sel_categoryid = isset($_GET['categoryid']) ? $_GET['categoryid'] : 0;
        $categoryObj->setVar('categoryid', $sel_categoryid);

        PublisherUtils::openCollapsableBar('createitemtable', 'createitemicon', _AM_PUBLISHER_ITEM_CREATING, _AM_PUBLISHER_ITEM_CREATING_DSC);
    }

    /* @var $sform PublisherItemForm */
    $sform = $publisher->getForm($itemObj, 'item');
    $sform->setTitle(_AM_PUBLISHER_ITEMS);
    $sform->assign($formTpl);
    $formTpl->display('module:publisher/publisher_submit.tpl');

    PublisherUtils::closeCollapsableBar('edititemtable', 'edititemicon');

    PublisherUtils::openCollapsableBar('pagewraptable', 'pagewrapicon', _AM_PUBLISHER_PAGEWRAP, _AM_PUBLISHER_PAGEWRAPDSC);

    $dir = PublisherUtils::getUploadDir(true, 'content');

    if (!preg_match('/777/i', decoct(fileperms($dir)))) {
        echo "<font color='FF0000'><h4>" . _AM_PUBLISHER_PERMERROR . "</h4></font>";
    }

    // Upload File
    echo "<form name='form_name2' id='form_name2' action='pw_upload_file.php' method='post' enctype='multipart/form-data'>";
    echo "<table cellspacing='1' width='100%' class='outer'>";
    echo "<tr><th colspan='2'>" . _AM_PUBLISHER_UPLOAD_FILE . "</th></tr>";
    echo "<tr valign='top' align='left'><td class='head'>" . _AM_PUBLISHER_SEARCH_PW . "</td><td class='even'><input type='file' name='fileupload' id='fileupload' size='30' /></td></tr>";
    echo "<tr valign='top' align='left'><td class='head'><input type='hidden' name='MAX_FILE_SIZE' id='op' value='500000' /></td><td class='even'><input type='submit' name='submit' value='" . _AM_PUBLISHER_UPLOAD . "' /></td></tr>";
    echo "<input type='hidden' name='backto' value='$publisher_current_page'/>";
    echo "</table>";
    echo "</form>";

    // Delete File
    $form = new Xoops\Form\ThemeForm(_CO_PUBLISHER_DELETEFILE, "form_name", "pw_delete_file.php");

    $pWrap_select = new Xoops\Form\Select(PublisherUtils::getUploadDir(true, 'content'), "address");
    $folder = dir($dir);
    if (is_object($folder)) {
        while ($file = $folder->read()) {
            if ($file !== "." && $file !== "..") {
                $pWrap_select->addOption($file, $file);
            }
        }
        $folder->close();
        $form->addElement($pWrap_select);
    }

    $delfile = "delfile";
    $form->addElement(new Xoops\Form\Hidden('op', $delfile));
    $submit = new Xoops\Form\Button("", "submit", _AM_PUBLISHER_BUTTON_DELETE, "submit");
    $form->addElement($submit);

    $form->addElement(new Xoops\Form\Hidden('backto', $publisher_current_page));
    $form->display();

    PublisherUtils::closeCollapsableBar('pagewraptable', 'pagewrapicon');
}

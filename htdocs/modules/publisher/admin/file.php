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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/admin_header.php';
$xoops = Xoops::getInstance();

$op = Request::getString('op');

function publisher_editFile($showmenu = false, $fileid = 0, $itemid = 0)
{
    $publisher = Publisher::getInstance();
    $xoops = Xoops::getInstance();

    // if there is a parameter, and the id exists, retrieve data: we're editing a file
    if ($fileid != 0) {
        // Creating the File object
        /* @var $fileObj PublisherFile */
        $fileObj = $publisher->getFileHandler()->get($fileid);

        if ($fileObj->notLoaded()) {
            $xoops->redirect("javascript:history.go(-1)", 1, _AM_PUBLISHER_NOFILESELECTED);
        }

        if ($showmenu) {
            //publisher_adminMenu(2, _AM_PUBLISHER_FILE . " > " . _AM_PUBLISHER_EDITING);
        }

        echo "<br />\n";
        echo "<span style='color: #2F5376; font-weight: bold; font-size: 16px; margin: 6px 06 0 0; '>" . _AM_PUBLISHER_FILE_EDITING . "</span>";
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . _AM_PUBLISHER_FILE_EDITING_DSC . "</span>";
        PublisherUtils::openCollapsableBar('editfile', 'editfileicon', _AM_PUBLISHER_FILE_INFORMATIONS);
    } else {
        // there's no parameter, so we're adding an item
        $fileObj = $publisher->getFileHandler()->create();
        $fileObj->setVar('itemid', $itemid);
        if ($showmenu) {
            //publisher_adminMenu(2, _AM_PUBLISHER_FILE . " > " . _AM_PUBLISHER_FILE_ADD);
        }
        echo "<span style='color: #2F5376; font-weight: bold; font-size: 16px; margin: 6px 06 0 0; '>" . _AM_PUBLISHER_FILE_ADDING . "</span>";
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . _AM_PUBLISHER_FILE_ADDING_DSC . "</span>";
        PublisherUtils::openCollapsableBar('addfile', 'addfileicon', _AM_PUBLISHER_FILE_INFORMATIONS);
    }

    // FILES UPLOAD FORM
    $files_form = $publisher->getForm($fileObj, 'file');
    $files_form->display();

    if ($fileid != 0) {
        PublisherUtils::closeCollapsableBar('editfile', 'editfileicon');
    } else {
        PublisherUtils::closeCollapsableBar('addfile', 'addfileicon');
    }

}

$false = false;
/* -- Available operations -- */
switch ($op) {
    case "uploadfile":
        PublisherUtils::uploadFile(false, true, $false);
        exit;
        break;

    case "uploadanother":
        PublisherUtils::uploadFile(true, true, $false);
        exit;
        break;

    case "mod":
        $fileid = isset($_GET['fileid']) ? $_GET['fileid'] : 0;
        $itemid = isset($_GET['itemid']) ? $_GET['itemid'] : 0;
        if (($fileid == 0) && ($itemid == 0)) {
            $xoops->redirect("javascript:history.go(-1)", 3, _AM_PUBLISHER_NOITEMSELECTED);
        }

        PublisherUtils::cpHeader();
        publisher_editFile(true, $fileid, $itemid);
        break;

    case "modify":
        $fileid = isset($_POST['fileid']) ? (int)($_POST['fileid']) : 0;

        // Creating the file object
        /* @var $fileObj PublisherFile */
        if ($fileid != 0) {
            $fileObj = $publisher->getFileHandler()->get($fileid);
        } else {
            $fileObj = $publisher->getFileHandler()->create();
        }

        // Putting the values in the file object
        $fileObj->setVar('name', $_POST['name']);
        $fileObj->setVar('description', $_POST['description']);
        $fileObj->setVar('status', (int)($_POST['file_status']));

        // Storing the file
        if (!$fileObj->store()) {
            $xoops->redirect('item.php?op=mod&itemid=' . $fileObj->getVar('itemid'), 3, _AM_PUBLISHER_FILE_EDITING_ERROR . PublisherUtils::formatErrors($fileObj->getErrors()));
            exit;
        }

        $xoops->redirect('item.php?op=mod&itemid=' . $fileObj->getVar('itemid'), 2, _AM_PUBLISHER_FILE_EDITING_SUCCESS);
        exit();
        break;

    case "del":

        $fileid = isset($_POST['fileid']) ? (int)($_POST['fileid']) : 0;
        $fileid = isset($_GET['fileid']) ? (int)($_GET['fileid']) : $fileid;

        $fileObj = $publisher->getFileHandler()->get($fileid);

        $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 0;
        $title = isset($_POST['title']) ? $_POST['title'] : '';

        if ($confirm) {
            if (!$publisher->getFileHandler()->delete($fileObj)) {
                $xoops->redirect('item.php', 2, _AM_PUBLISHER_FILE_DELETE_ERROR);
            }
            $xoops->redirect('item.php', 2, sprintf(_AM_PUBLISHER_FILEISDELETED, $fileObj->getVar('name')));
        } else {
            // no confirm: show deletion condition
            $fileid = isset($_GET['fileid']) ? (int)($_GET['fileid']) : 0;

            PublisherUtils::cpHeader();
            echo $xoops->confirm(array('op' => 'del', 'fileid' => $fileObj->getVar('fileid'), 'confirm' => 1, 'name' => $fileObj->getVar('name')), 'file.php', _AM_PUBLISHER_DELETETHISFILE . " <br />" . $fileObj->getVar('name') . " <br /> <br />", _AM_PUBLISHER_DELETE);
            $xoops->footer();
        }
        exit();
        break;

    case "default":
    default:
        PublisherUtils::cpHeader();
        break;
}
$xoops->footer();

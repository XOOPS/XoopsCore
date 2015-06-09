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
 * @subpackage      Action
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$publisher = Publisher::getInstance();
$myts = MyTextSanitizer::getInstance();

$itemid = Request::getInt('itemid');
$item_page_id = Request::getInt('page', -1);

if ($itemid == 0) {
    $xoops->redirect("javascript:history.go(-1)", 1, _MD_PUBLISHER_NOITEMSELECTED);
}

// Creating the item object for the selected item
/* @var $itemObj PublisherItem */
$itemObj = $publisher->getItemHandler()->get($itemid);

// if the selected item was not found, exit
if (!$itemObj) {
    $xoops->redirect("javascript:history.go(-1)", 1, _MD_PUBLISHER_NOITEMSELECTED);
}

$xoops->header('module:publisher/publisher_item.tpl');
$xoopsTpl = $xoops->tpl();
$xoTheme = $xoops->theme();
$xoTheme->addStylesheet(PUBLISHER_URL . '/css/jquery.popeye.style.css');
$xoTheme->addBaseScriptAssets('@jquery');
$xoTheme->addScript(PUBLISHER_URL . '/js/jquery.popeye-2.0.4.js');
$xoTheme->addBaseScriptAssets('modules/publisher/js/publisher.js');

XoopsLoad::loadFile($publisher->path('footer.php'));

// Creating the category object that holds the selected item
$categoryObj = $publisher->getCategoryHandler()->get($itemObj->getVar('categoryid'));

// Check user permissions to access that category of the selected item
if (!$itemObj->accessGranted()) {
    $xoops->redirect("javascript:history.go(-1)", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

// Update the read counter of the selected item
if (!$xoops->isUser() || (PublisherUtils::IsUserAdmin() && $publisher->getConfig('item_admin_hits') == 1) || ($xoops->isUser() && !PublisherUtils::IsUserAdmin())) {
    $itemObj->updateCounter();
}

// creating the Item objects that belong to the selected category
switch ($publisher->getConfig('format_order_by')) {
    case 'title':
        $sort = 'title';
        $order = 'ASC';
        break;

    case 'date':
        $sort = 'datesub';
        $order = 'DESC';
        break;

    default:
        $sort = 'weight';
        $order = 'ASC';
        break;
}

if ($publisher->getConfig('item_other_items_type') == "previous_next") {
    // Retrieving the next and previous object
    $previous_item_link = '';
    $previous_item_url = '';
    $next_item_link = '';
    $next_item_url = '';

    $previousObj = $publisher->getItemHandler()->getPreviousPublished($itemObj);
    $nextObj = $publisher->getItemHandler()->getNextPublished($itemObj);
    if (is_object($previousObj)) {
        $previous_item_link = $previousObj->getItemLink();
        $previous_item_url = $previousObj->getItemUrl();
    }

    if (is_object($nextObj)) {
        $next_item_link = $nextObj->getItemLink();
        $next_item_url = $nextObj->getItemUrl();
    }
    unset($previousObj, $nextObj);
    $xoopsTpl->assign('previous_item_link', $previous_item_link);
    $xoopsTpl->assign('next_item_link', $next_item_link);
    $xoopsTpl->assign('previous_item_url', $previous_item_url);
    $xoopsTpl->assign('next_item_url', $next_item_url);
}

//CAREFUL!! with many items this will exhaust memory
if ($publisher->getConfig('item_other_items_type') == "all") {
    $itemsObj = $publisher->getItemHandler()->getAllPublished(0, 0, $categoryObj->getVar('categoryid'), $sort, $order, '', true, true);
    $items = array();
    /* @var $theitemObj PublisherItem */
    foreach ($itemsObj as $theitemObj) {
        $theitem['titlelink'] = $theitemObj->getItemLink();
        $theitem['datesub'] = $theitemObj->datesub();
        $theitem['counter'] = $theitemObj->getVar('counter');
        if ($theitemObj->getVar('itemid')== $itemObj->getVar('itemid')) {
            $theitem['titlelink'] = $theitemObj->title();
        }
        $items[] = $theitem;
        unset($theitem);
    }
    unset($itemsObj);
    $xoopsTpl->assign('items', $items);
}

// Populating the smarty variables with informations related to the selected item
$item = $itemObj->toArray($item_page_id);
$xoopsTpl->assign('show_subtitle', $publisher->getConfig('item_disp_subtitle'));

if ($itemObj->getVar('pagescount') > 0) {
    if ($item_page_id == -1) {
        $item_page_id = 0;
    }
    $pagenav = new XoopsPageNav($itemObj->getVar('pagescount'), 1, $item_page_id, 'page', 'itemid=' . $itemObj->getVar('itemid'));
    $xoopsTpl->assign('pagenav', $pagenav->renderNav());
}

// Creating the files object associated with this item
$file = array();
$files = array();
$embeded_files = array();
$filesObj = $itemObj->getFiles();

// check if user has permission to modify files
$hasFilePermissions = true;
if (!(PublisherUtils::IsUserAdmin() || PublisherUtils::IsUserModerator($itemObj))) {
    $hasFilePermissions = false;
}

/* @var $fileObj PublisherFile */
foreach ($filesObj as $fileObj) {
    $file = array();
    $file['mod'] = false;
    if ($hasFilePermissions || ($xoops->isUser() && $fileObj->getVar('uid') == $xoops->user->getVar('uid') )) {
        $file['mod'] = true;
    }

    if ($fileObj->getVar('mimetype') == 'application/x-shockwave-flash') {
        $file['content'] = $fileObj->displayFlash();
        if (strpos($item['maintext'], '[flash-' . $fileObj->getVar('fileid') . ']')) {
            $item['maintext'] = str_replace('[flash-' . $fileObj->getVar('fileid') . ']', $file['content'], $item['maintext']);
        } else {
            $embeded_files[] = $file;
        }
    } else {
        $file['fileid'] = $fileObj->getVar('fileid');
        $file['name'] = $fileObj->getVar('name');
        $file['description'] = $fileObj->getVar('description');
        $file['name'] = $fileObj->getVar('name');
        $file['type'] = $fileObj->getVar('mimetype');
        $file['datesub'] = $fileObj->datesub();
        $file['hits'] = $fileObj->getVar('counter');
        $files[] = $file;
    }
}

$item['files'] = $files;
$item['embeded_files'] = $embeded_files;
unset($file, $embeded_files, $filesObj, $fileObj);

// Language constants
$xoopsTpl->assign('mail_link', 'mailto:?subject=' . sprintf(_CO_PUBLISHER_INTITEM, $xoops->getConfig('sitename')) . '&amp;body=' . sprintf(_CO_PUBLISHER_INTITEMFOUND, $xoops->getConfig('sitename')) . ': ' . $itemObj->getItemUrl());
$xoopsTpl->assign('itemid', $itemObj->getVar('itemid'));
$xoopsTpl->assign('sectionname', $publisher->getModule()->getVar('name'));
$xoopsTpl->assign('modulename', $publisher->getModule()->getVar('dirname'));
$xoopsTpl->assign('module_home', PublisherUtils::moduleHome($publisher->getConfig('format_linked_path')));
$xoopsTpl->assign('categoryPath', $item['categoryPath'] . " > " . $item['title']);
$xoopsTpl->assign('commentatarticlelevel', $publisher->getConfig('perm_com_art_level'));
$xoopsTpl->assign('com_rule', $publisher->getConfig('com_rule'));
$xoopsTpl->assign('other_items', $publisher->getConfig('item_other_items_type'));
$xoopsTpl->assign('itemfooter', $myts->displayTarea($publisher->getConfig('item_footer'), 1));
$xoopsTpl->assign('perm_author_items', $publisher->getConfig('perm_author_items'));

// tags support
if ($xoops->isActiveModule('tag')) {
    include_once \XoopsBaseConfig::get('root-path') . '/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tagbar', tagBar($itemid, $catid = 0));
}

/**
 * Generating meta information for this page
 */
$publisher_metagen = new PublisherMetagen($itemObj->getVar('title'), $itemObj->getVar('meta_keywords', 'n'), $itemObj->getVar('meta_description', 'n'), $itemObj->getCategoryPath());
$publisher_metagen->createMetaTags();

// Include the comments if the selected ITEM supports comments
if ($xoops->isActiveModule('comments') && (($itemObj->getVar('cancomment') == 1) || !$publisher->getConfig('perm_com_art_level')) && ($publisher->getConfig('com_rule') <> 0)) {
    $xoopsTpl->assign('canComment', true);
    //Comments::getInstance()->renderView();
    // Problem with url_rewrite and posting comments :
    /*
    $xoopsTpl->assign(array(
        'editcomment_link' => PUBLISHER_URL . '/comment_edit.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . $link_extra,
        'deletecomment_link' => PUBLISHER_URL . '/comment_delete.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . $link_extra,
        'replycomment_link' => PUBLISHER_URL . '/comment_reply.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . $link_extra));
    $xoopsTpl->_tpl_vars['commentsnav'] = str_replace("self.location.href='", "self.location.href='" . PUBLISHER_URL . '/', $xoopsTpl->_tpl_vars['commentsnav']);
    */
}

// Include support for AJAX rating
if ($publisher->getConfig('perm_rating')) {
    $xoopsTpl->assign('rating_enabled', true);
    $item['ratingbar'] = PublisherUtils::ratingBar($itemid);
    $xoTheme->addScript(PUBLISHER_URL . '/js/behavior.js');
    $xoTheme->addScript(PUBLISHER_URL . '/js/rating.js');
}

$xoopsTpl->assign('item', $item);
$xoops->footer();

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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author          ohwada
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/admin_header.php';
$myts = MyTextSanitizer::getInstance();

$importFromModuleName = "XF-Section " . @$_POST['xfs_version'];

$scriptname = "xfsection.php";

$op = 'start';

if (isset($_POST['op']) && ($_POST['op'] == 'go')) {
    $op = $_POST['op'];
}

if ($op == 'start') {

    PublisherUtils::cpHeader();
    //publisher_adminMenu(-1, _AM_PUBLISHER_IMPORT);
    PublisherUtils::openCollapsableBar('xfsectionimport', 'xfsectionimporticon', sprintf(_AM_PUBLISHER_IMPORT_FROM, $importFromModuleName), _AM_PUBLISHER_IMPORT_INFO);

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("xfs_category"));
    list ($totalCat) = $xoopsDB->fetchRow($result);

    if ($totalCat == 0) {
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . _AM_PUBLISHER_IMPORT_NOCATSELECTED . "</span>";
    } else {
        include_once \XoopsBaseConfig::get('root-path') . '/class/xoopstree.php';

        $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("xfs_article"));
        list ($totalArticles) = $xoopsDB->fetchRow($result);

        if ($totalArticles == 0) {
            echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . sprintf(_AM_PUBLISHER_IMPORT_MODULE_FOUND_NO_ITEMS, $importFromModuleName, $totalArticles) . "</span>";
        } else {
            echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . sprintf(_AM_PUBLISHER_IMPORT_MODULE_FOUND, $importFromModuleName, $totalArticles, $totalCat) . "</span>";

            $form = new Xoops\Form\ThemeForm(_AM_PUBLISHER_IMPORT_SETTINGS, 'import_form', PUBLISHER_ADMIN_URL . "/import/$scriptname");

            // Categories to be imported
            $sql = "SELECT cat.id, cat.pid, cat.title, COUNT(art.articleid) FROM " . $xoopsDB->prefix("xfs_category") . " AS cat INNER JOIN " . $xoopsDB->prefix("xfs_article") . " AS art ON cat.id=art.categoryid GROUP BY art.categoryid";
            $result = $xoopsDB->query($sql);
            $cat_cbox_values = array();
            $cat_cbox_options = array();
            while (list ($cid, $pid, $cat_title, $art_count) = $xoopsDB->fetchRow($result)) {
                $cat_title = $myts->displayTarea($cat_title);
                $cat_cbox_options[$cid] = "$cat_title ($art_count)";
            }
            $cat_label = new Xoops\Form\Label(_AM_PUBLISHER_IMPORT_CATEGORIES, implode("<br />", $cat_cbox_options));
            $cat_label->setDescription(_AM_PUBLISHER_IMPORT_CATEGORIES_DSC);
            $form->addElement($cat_label);

            // SmartFAQ parent category
            $mytree = new XoopsTree($xoopsDB->prefix("publisher_categories"), "categoryid", "parentid");
            ob_start();
            $mytree->makeMySelBox("name", "weight", $preset_id = 0, $none = 1, $sel_name = "parent_category");

            $parent_cat_sel = new Xoops\Form\Label(_AM_PUBLISHER_IMPORT_PARENT_CATEGORY, ob_get_contents());
            $parent_cat_sel->setDescription(_AM_PUBLISHER_IMPORT_PARENT_CATEGORY_DSC);
            $form->addElement($parent_cat_sel);
            ob_end_clean();

            $form->addElement(new Xoops\Form\Hidden('op', 'go'));
            $form->addElement(new Xoops\Form\Button('', 'import', _AM_PUBLISHER_IMPORT, 'submit'));

            $form->addElement(new Xoops\Form\Hidden('from_module_version', $_POST['from_module_version']));

            $form->display();
        }
    }

    PublisherUtils::closeCollapsableBar('xfsectionimport', 'xfsectionimporticon');
    $xoops->footer();
}

if ($op == 'go') {
    PublisherUtils::cpHeader();
    //publisher_adminMenu(-1, _AM_PUBLISHER_IMPORT);
    PublisherUtils::openCollapsableBar('xfsectionimportgo', 'xfsectionimportgoicon', sprintf(_AM_PUBLISHER_IMPORT_FROM, $importFromModuleName), _AM_PUBLISHER_IMPORT_RESULT);

    $cnt_imported_cat = 0;
    $cnt_imported_articles = 0;

    $parentId = $_POST['parent_category'];


    $sql = "SELECT * FROM " . $xoopsDB->prefix("xfs_category") . " ORDER by orders";

    $resultCat = $xoopsDB->query($sql);

    $newCatArray = array();
    while ($arrCat = $xoopsDB->fetchArray($resultCat)) {
        $categoryObj = $publisher->getCategoryHandler()->create();

        $newCat = array();

        $newCat['oldid'] = $arrCat['id'];
        $newCat['oldpid'] = $arrCat['pid'];

        $categoryObj->setVar('parentid', $arrCat['pid']);

        $categoryObj->setVar('weight', $arrCat['orders']);
        $categoryObj->setGroups_read(explode(" ", trim($arrCat['groupid'])));
        $categoryObj->setGroups_submit(explode(" ", trim($arrCat['editaccess'])));
        $categoryObj->setVar('name', $arrCat['title']);
        $categoryObj->setVar('description', $arrCat['description']);

        // Category image
        if (($arrCat['imgurl'] != 'blank.gif') && ($arrCat['imgurl'])) {
            if (copy(\XoopsBaseConfig::get('root-path') . "/modules/xfsection/images/category/" . $arrCat['imgurl'], PUBLISHER_UPLOADS_PATH . "/images/category/" . $arrCat['imgurl'])) {
                $categoryObj->setVar('image', $arrCat['imgurl']);
            }
        }

        if (!$categoryObj->store(false)) {
            echo sprintf(_AM_PUBLISHER_IMPORT_CATEGORY_ERROR, $arrCat['title']) . "<br/>";
            continue;
        }

        $newCat['newid'] = $categoryObj->getVar('categoryid');
        // Saving category permissions
        PublisherUtils::saveCategoryPermissions($categoryObj->getGroups_read(), $categoryObj->getVar('categoryid'), 'category_read');
        PublisherUtils::saveCategoryPermissions($categoryObj->getGroups_submit(), $categoryObj->getVar('categoryid'), 'item_submit');

        ++$cnt_imported_cat;

        echo sprintf(_AM_PUBLISHER_IMPORT_CATEGORY_SUCCESS, $categoryObj->getVar('name')) . "<br\>";

        $sql = "SELECT * FROM " . $xoopsDB->prefix("xfs_article") . " WHERE categoryid=" . $arrCat['id'] . " ORDER BY weight";
        $resultArticles = $xoopsDB->query($sql);
        while ($arrArticle = $xoopsDB->fetchArray($resultArticles)) {
            // insert article
            $itemObj = $publisher->getItemHandler()->create();

            $itemObj->setVar('categoryid', $categoryObj->getVar('categoryid'));
            $itemObj->setVar('title', $arrArticle['title']);
            $itemObj->setVar('uid', $arrArticle['uid']);
            $itemObj->setVar('summary', $arrArticle['summary']);
            $itemObj->setVar('body', $arrArticle['maintext']);
            $itemObj->setVar('counter', $arrArticle['counter']);
            $itemObj->setVar('datesub', $arrArticle['created']);
            $itemObj->setVar('weight', $arrArticle['weight']);
            $itemObj->setVar('dohtml', !$arrArticle['nohtml']);
            $itemObj->setVar('dosmiley', !$arrArticle['nosmiley']);
            $itemObj->setVar('dobr', !$arrArticle['nobr']);
            $itemObj->setGroups_read(explode(" ", trim($arrArticle['groupid'])));

            // status
            $status = _PUBLISHER_STATUS_PUBLISHED;
            if ($arrArticle['offline']) {
                $status = _PUBLISHER_STATUS_OFFLINE;
            }
            $itemObj->setVar('status', $status);

            // HTML Wrap
            if ($arrArticle['htmlpage']) {
                $pagewrap_filename = \XoopsBaseConfig::get('root-path') . "/modules/xfsection/html/" . $arrArticle['htmlpage'];
                if (XoopsLoad::fileExists($pagewrap_filename)) {
                    if (copy($pagewrap_filename, PUBLISHER_UPLOADS_PATH . "/content/" . $arrArticle['htmlpage'])) {
                        $itemObj->setVar('body', "[pagewrap=" . $arrArticle['htmlpage'] . "]");
                        echo sprintf("&nbsp;&nbsp;&nbsp;&nbsp;" . _AM_PUBLISHER_IMPORT_ARTICLE_WRAP, $arrArticle['htmlpage']) . "<br/>";
                    }
                }
            }

            if (!$itemObj->store()) {
                echo sprintf("  " . _AM_PUBLISHER_IMPORT_ARTICLE_ERROR, $arrArticle['title']) . "<br/>";
                continue;
            } else {
                // Linkes files

                $sql = "SELECT * FROM " . $xoopsDB->prefix("xfs_files") . " WHERE articleid=" . $arrArticle['articleid'];
                $resultFiles = $xoopsDB->query($sql);
                $allowed_mimetypes = '';
                while ($arrFile = $xoopsDB->fetchArray($resultFiles)) {

                    $filename = \XoopsBaseConfig::get('root-path') . "/modules/xfsection/cache/uploaded/" . $arrFile['filerealname'];
                    if (XoopsLoad::fileExists($filename)) {
                        if (copy($filename, PUBLISHER_UPLOADS_PATH . "/" . $arrFile['filerealname'])) {
                            $fileObj = $publisher->getFileHandler()->create();
                            $fileObj->setVar('name', $arrFile['fileshowname']);
                            $fileObj->setVar('description', $arrFile['filedescript']);
                            $fileObj->setVar('status', _PUBLISHER_STATUS_FILE_ACTIVE);
                            $fileObj->setVar('uid', $arrArticle['uid']);
                            $fileObj->setVar('itemid', $itemObj->getVar('itemid'));
                            $fileObj->setVar('mimetype', $arrFile['minetype']);
                            $fileObj->setVar('datesub', $arrFile['date']);
                            $fileObj->setVar('counter', $arrFile['counter']);
                            $fileObj->setVar('filename', $arrFile['filerealname']);

                            if ($fileObj->store($allowed_mimetypes, true, false)) {
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;" . sprintf(_AM_PUBLISHER_IMPORTED_ARTICLE_FILE, $arrFile['filerealname']) . "<br />";
                            }
                        }
                    }
                }

                $newArticleArray[$arrArticle['articleid']] = $itemObj->getVar('itemid');
                echo "&nbsp;&nbsp;" . sprintf(_AM_PUBLISHER_IMPORTED_ARTICLE, $itemObj->title()) . "<br />";
                ++$cnt_imported_articles;
            }
        }
        $newCatArray[$newCat['oldid']] = $newCat;
        unset($newCat);
        echo "<br/>";
    }
    // Looping through cat to change the pid to the new pid
    foreach ($newCatArray as $oldid => $newCat) {
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('categoryid', $newCat['newid']));
        $oldpid = $newCat['oldpid'];
        if ($oldpid == 0) {
            $newpid = $parentId;
        } else {
            $newpid = $newCatArray[$oldpid]['newid'];
        }
        $publisher->getCategoryHandler()->updateAll('parentid', $newpid, $criteria);
        unset($criteria);
    }

    // Looping through the comments to link them to the new articles and module
    echo _AM_PUBLISHER_IMPORT_COMMENTS . "<br />";

    $module_handler = xoops_getHandler('module');
    $moduleObj = $module_handler->getByDirname('xfsection');
    $news_module_id = $moduleObj->getVar('mid');

    $publisher_module_id = $publisher->getModule()->mid();

    $comment_handler = xoops_getHandler('comment');
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('com_modid', $news_module_id));
    $comments = $comment_handler->getObjects($criteria);
    foreach ($comments as $comment) {
        $comment->setVar('com_itemid', $newArticleArray[$comment->getVar('com_itemid')]);
        $comment->setVar('com_modid', $publisher_module_id);
        $comment->setNew();
        if (!$comment_handler->insert($comment)) {
            echo "&nbsp;&nbsp;" . sprintf(_AM_PUBLISHER_IMPORTED_COMMENT_ERROR, $comment->getVar('com_title')) . "<br />";
        } else {
            echo "&nbsp;&nbsp;" . sprintf(_AM_PUBLISHER_IMPORTED_COMMENT, $comment->getVar('com_title')) . "<br />";
        }

    }

    echo "<br/><br/>Done.<br/>";
    echo sprintf(_AM_PUBLISHER_IMPORTED_CATEGORIES, $cnt_imported_cat) . "<br/>";
    echo sprintf(_AM_PUBLISHER_IMPORTED_ARTICLES, $cnt_imported_articles) . "<br/>";
    echo "<br/><a href='" . publisher_URL . "'>" . _AM_PUBLISHER_IMPORT_GOTOMODULE . "</a><br/>";

    PublisherUtils::closeCollapsableBar('xfsectionimportgo', 'xfsectionimportgoicon');
    $xoops->footer();
}

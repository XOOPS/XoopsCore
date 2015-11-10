<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Form\ContainerInterface;
use Xoops\Form\Tab;
use Xoops\Form\TabTray;

include_once dirname(dirname(__DIR__)) . '/include/common.php';

$publisher = Publisher::getInstance();

/**
 *  Publisher form class
 *
 * @category  PublisherItemForm
 * @package   Publisher
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2015 The XUUPS Project (http://sourceforge.net/projects/xuups/)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class PublisherItemForm extends Xoops\Form\SimpleForm
{
    private $checkperm = true;

    private $tabs = array(
        _CO_PUBLISHER_TAB_MAIN  => 'mainTab', _CO_PUBLISHER_TAB_IMAGES => 'imagesTab',
        _CO_PUBLISHER_TAB_FILES => 'filesTab', _CO_PUBLISHER_TAB_OTHERS => 'othersTab'
    );

    private $mainTab = array(
        _PUBLISHER_SUBTITLE, _PUBLISHER_ITEM_SHORT_URL, _PUBLISHER_ITEM_TAG, _PUBLISHER_SUMMARY, _PUBLISHER_DOHTML,
        _PUBLISHER_DOSMILEY, _PUBLISHER_DOXCODE, _PUBLISHER_DOIMAGE, _PUBLISHER_DOLINEBREAK, _PUBLISHER_DATESUB,
        _PUBLISHER_STATUS, _PUBLISHER_AUTHOR_ALIAS, _PUBLISHER_NOTIFY, _PUBLISHER_AVAILABLE_PAGE_WRAP, _PUBLISHER_UID
    );

    private $imagesTab = array(
        _PUBLISHER_IMAGE_ITEM
    );

    private $filesTab = array(
        _PUBLISHER_ITEM_UPLOAD_FILE
    );

    private $othersTab = array(
        _PUBLISHER_ITEM_META_KEYWORDS, _PUBLISHER_ITEM_META_DESCRIPTION, _PUBLISHER_WEIGHT, _PUBLISHER_ALLOWCOMMENTS
    );

    /**
     * __construct
     *
     * @param PublisherItem $obj source object for form variables
     */
    public function __construct(PublisherItem $obj)
    {
        $xoops = Xoops::getInstance();

        parent::__construct('title', 'form', $xoops->getEnv('PHP_SELF'));
        $this->setExtra('enctype="multipart/form-data"');

        $tabTray = new TabTray('', 'uniqueid');

        $mainTab = new Tab(_CO_PUBLISHER_TAB_MAIN, 'maintab');
        $this->buildMainTab($obj, $mainTab);
        $tabTray->addElement($mainTab);

        if ($xoops->isActiveModule('images') && $this->hasTab(_CO_PUBLISHER_TAB_IMAGES)) {
            $imagesTab = new Tab(_CO_PUBLISHER_TAB_IMAGES, 'imagestab');
            $this->buildImagesTab($obj, $imagesTab);
            $tabTray->addElement($imagesTab);
        }

        if ($this->hasTab(_CO_PUBLISHER_TAB_FILES)) {
            $filesTab = new Tab(_CO_PUBLISHER_TAB_FILES, 'filestab');
            $this->buildFilesTab($obj, $filesTab);
            $tabTray->addElement($filesTab);
        }

        if ($this->hasTab(_CO_PUBLISHER_TAB_OTHERS)) {
            $othersTab = new Tab(_CO_PUBLISHER_TAB_OTHERS, 'otherstab');
            $this->buildOthersTab($obj, $othersTab);
            $tabTray->addElement($othersTab);
        }
        $this->addElement($tabTray);

        //COMMON TO ALL TABS

        $buttonTray = new Xoops\Form\ElementTray('', '');

        if (!$obj->isNew()) {
            $buttonTray->addElement(new Xoops\Form\Button('', 'additem', XoopsLocale::A_SUBMIT, 'submit')); //orclone

        } else {
            $buttonTray->addElement(new Xoops\Form\Button('', 'additem', _CO_PUBLISHER_CREATE, 'submit'));
            $buttonTray->addElement(new Xoops\Form\Button('', '', _CO_PUBLISHER_CLEAR, 'reset'));
        }

        $buttonTray->addElement(new Xoops\Form\Button('', 'preview', _CO_PUBLISHER_PREVIEW, 'submit'));

        $buttonCancel = new Xoops\Form\Button('', '', _CO_PUBLISHER_CANCEL, 'button');
        $buttonCancel->set('onclick', 'history.go(-1);');
        $buttonTray->addElement($buttonCancel);

        $this->addElement($buttonTray);

        $hidden = new Xoops\Form\Hidden('itemid', $obj->getVar('itemid'));
        $this->addElement($hidden);
        unset($hidden);
    }

    /**
     * Build the main tab
     *
     * @param PublisherItem      $obj     data source
     * @param ContainerInterface $mainTab add elements to this tab/form
     *
     * @return void
     */
    private function buildMainTab(PublisherItem $obj, ContainerInterface $mainTab)
    {
        $xoops = Xoops::getInstance();
        $publisher = Publisher::getInstance();

        // Category
        $category_select = new Xoops\Form\Select(_CO_PUBLISHER_CATEGORY, 'categoryid', $obj->getVar('categoryid', 'e'));
        $category_select->setDescription(_CO_PUBLISHER_CATEGORY_DSC);
        $category_select->addOptionArray($publisher->getCategoryHandler()->getCategoriesForSubmit());
        $mainTab->addElement($category_select);

        // ITEM TITLE
        $mainTab->addElement(
            new Xoops\Form\Text(_CO_PUBLISHER_TITLE, 'title', 50, 255, $obj->getVar('title', 'e')),
            true
        );

        // SUBTITLE
        if ($this->isGranted(_PUBLISHER_SUBTITLE)) {
            $mainTab->addElement(
                new Xoops\Form\Text(_CO_PUBLISHER_SUBTITLE, 'subtitle', 50, 255, $obj->getVar('subtitle', 'e'))
            );
        }

        // SHORT URL
        if ($this->isGranted(_PUBLISHER_ITEM_SHORT_URL)) {
            $text_short_url = new Xoops\Form\Text(
                _CO_PUBLISHER_ITEM_SHORT_URL,
                'item_short_url',
                50,
                255,
                $obj->getVar('short_url', 'e')
            );
            $text_short_url->setDescription(_CO_PUBLISHER_ITEM_SHORT_URL_DSC);
            $mainTab->addElement($text_short_url);
        }

        // TAGS
        if ($xoops->isActiveModule('tag') && $this->isGranted(_PUBLISHER_ITEM_TAG)) {
            include_once $xoops->path('modules/tag/include/formtag.php');
            $text_tags = new Tag('item_tag', 60, 255, $obj->getVar('item_tag', 'e'), 0);
            $mainTab->addElement($text_tags);
        }

        $this->buildEditors($obj, $mainTab);
        $this->buildTSOptions($obj, $mainTab);

        // Available pages to wrap
        if ($this->isGranted(_PUBLISHER_AVAILABLE_PAGE_WRAP)) {
            $wrap_pages = XoopsLists::getHtmlListAsArray(PublisherUtils::getUploadDir(true, 'content'));
            $available_wrap_pages_text = array();
            foreach ($wrap_pages as $page) {
                $available_wrap_pages_text[] = "<span onclick='publisherPageWrap(\"body\", \"[pagewrap=$page] \");'"
                    . " onmouseover='style.cursor=\"pointer\"'>$page</span>";
            }
            $available_wrap_pages = new Xoops\Form\Label(
                _CO_PUBLISHER_AVAILABLE_PAGE_WRAP,
                implode(', ', $available_wrap_pages_text)
            );
            $available_wrap_pages->setDescription(_CO_PUBLISHER_AVAILABLE_PAGE_WRAP_DSC);
            $mainTab->addElement($available_wrap_pages);
        }

        // Uid
        /*  We need to retrieve the users manually because for some reason, on the frxoops.org server,
         the method users::getobjects encounters a memory error
         */
        // Trabis : well, maybe is because you are getting 6000 objects into memory , no??? LOL
        if ($this->isGranted(_PUBLISHER_UID)) {
            $uid_select =
                new Xoops\Form\SelectUser(_CO_PUBLISHER_UID, 'uid', true, array($obj->getVar('uid', 'e')), 1, false);
            $uid_select->setDescription(_CO_PUBLISHER_UID_DSC);
            $mainTab->addElement($uid_select);
        }

        // Author Alias
        if ($this->isGranted(_PUBLISHER_AUTHOR_ALIAS)) {
            $element = new Xoops\Form\Text(
                _CO_PUBLISHER_AUTHOR_ALIAS,
                'author_alias',
                50,
                255,
                $obj->getVar('author_alias', 'e')
            );
            $element->setDescription(_CO_PUBLISHER_AUTHOR_ALIAS_DSC);
            $mainTab->addElement($element);
            unset($element);
        }

        // STATUS
        if ($this->isGranted(_PUBLISHER_STATUS)) {
            $options = array(
                _PUBLISHER_STATUS_PUBLISHED => _CO_PUBLISHER_PUBLISHED,
                _PUBLISHER_STATUS_OFFLINE   => _CO_PUBLISHER_OFFLINE,
                _PUBLISHER_STATUS_SUBMITTED => _CO_PUBLISHER_SUBMITTED,
                _PUBLISHER_STATUS_REJECTED  => _CO_PUBLISHER_REJECTED
            );
            $status_select = new Xoops\Form\Select(_CO_PUBLISHER_STATUS, 'status', $obj->getVar('status'));
            $status_select->addOptionArray($options);
            $status_select->setDescription(_CO_PUBLISHER_STATUS_DSC);
            $mainTab->addElement($status_select);
            unset($status_select);
        }

        // Datesub
        if ($this->isGranted(_PUBLISHER_DATESUB)) {
            $datesub = ($obj->getVar('datesub') == 0) ? time() : $obj->getVar('datesub');
            $datesub_datetime = new Xoops\Form\DateTime(_CO_PUBLISHER_DATESUB, 'datesub', $datesub);
            $datesub_datetime->setDescription(_CO_PUBLISHER_DATESUB_DSC);
            $mainTab->addElement($datesub_datetime);
        }

        // NOTIFY ON PUBLISH
        if ($this->isGranted(_PUBLISHER_NOTIFY)) {
            $notify_radio = new Xoops\Form\RadioYesNo(_CO_PUBLISHER_NOTIFY, 'notify', $obj->getVar('notifypub'));
            $mainTab->addElement($notify_radio);
        }

    }

    /**
     * Build the summary and body editors
     *
     * @param PublisherItem      $obj     data source
     * @param ContainerInterface $mainTab add elements to this tab/form
     *
     * @return void
     */
    private function buildEditors(PublisherItem $obj, ContainerInterface $mainTab)
    {
        $xoops = Xoops::getInstance();
        $publisher = Publisher::getInstance();

        // SELECT EDITOR
        $allowed_editors = PublisherUtils::getEditors($publisher->getPermissionHandler()->getGrantedItems('editors'));

        $nohtml = false;
        if (count($allowed_editors) == 1) {
            $editor = $allowed_editors[0];
        } else {
            if (count($allowed_editors) > 0) {
                $editor = @$_POST['editor'];
                if (!empty($editor)) {
                    PublisherUtils::setCookieVar('publisher_editor', $editor);
                } else {
                    $editor = PublisherUtils::getCookieVar('publisher_editor');
                    if (empty($editor) && $xoops->isUser()) {
                        $editor = $xoops->user->getVar('publisher_editor'); // Need set through user profile
                    }
                }
                $editor = (empty($editor) || !in_array($editor, $allowed_editors))
                    ? $publisher->getConfig('submit_editor') : $editor;

                $form_editor = new Xoops\Form\SelectEditor($this, 'editor', $editor, $nohtml, $allowed_editors);
                $mainTab->addElement($form_editor);
            } else {
                $editor = $publisher->getConfig('submit_editor');
            }
        }

        $editor_configs = array();
        $editor_configs["rows"] = !$publisher->getConfig('submit_editor_rows')
            ? 35 : $publisher->getConfig('submit_editor_rows');
        $editor_configs["cols"] = !$publisher->getConfig('submit_editor_cols')
            ? 60 : $publisher->getConfig('submit_editor_cols');
        $editor_configs["width"] = !$publisher->getConfig('submit_editor_width')
            ? "100%" : $publisher->getConfig('submit_editor_width');
        $editor_configs["height"] = !$publisher->getConfig('submit_editor_height')
            ? "400px" : $publisher->getConfig('submit_editor_height');

        // SUMMARY
        if ($this->isGranted(_PUBLISHER_SUMMARY)) {
            // Description
            $editor_configs["name"] = "summary";
            $editor_configs["value"] = $obj->getVar('summary', 'e');
            $summary_text =
                new Xoops\Form\Editor(_CO_PUBLISHER_SUMMARY, $editor, $editor_configs, $nohtml, $onfailure = null);
            $summary_text->setDescription(_CO_PUBLISHER_SUMMARY_DSC);
            $mainTab->addElement($summary_text);
        }

        // BODY
        $editor_configs["name"] = "body";
        $editor_configs["value"] = $obj->getVar('body', 'e');
        $body_text = new Xoops\Form\Editor(_CO_PUBLISHER_BODY, $editor, $editor_configs, $nohtml, $onfailure = null);
        $body_text->setDescription(_CO_PUBLISHER_BODY_DSC);
        $mainTab->addElement($body_text);

    }

    /**
     * Build the option selectors for Text\Sanitizer display processing
     *
     * @param PublisherItem      $obj     data source
     * @param ContainerInterface $mainTab add elements to this tab/form
     *
     * @return void
     */
    private function buildTSOptions(PublisherItem $obj, ContainerInterface $mainTab)
    {
        // VARIOUS OPTIONS
        if ($this->isGranted(_PUBLISHER_DOHTML)) {
            $html_radio = new Xoops\Form\RadioYesNo(_CO_PUBLISHER_DOHTML, 'dohtml', $obj->getVar('dohtml'));
            $mainTab->addElement($html_radio);
        }
        if ($this->isGranted(_PUBLISHER_DOSMILEY)) {
            $smiley_radio = new Xoops\Form\RadioYesNo(_CO_PUBLISHER_DOSMILEY, 'dosmiley', $obj->getVar('dosmiley'));
            $mainTab->addElement($smiley_radio);
        }
        if ($this->isGranted(_PUBLISHER_DOXCODE)) {
            $xcode_radio = new Xoops\Form\RadioYesNo(_CO_PUBLISHER_DOXCODE, 'doxcode', $obj->getVar('doxcode'));
            $mainTab->addElement($xcode_radio);
        }
        if ($this->isGranted(_PUBLISHER_DOIMAGE)) {
            $image_radio = new Xoops\Form\RadioYesNo(_CO_PUBLISHER_DOIMAGE, 'doimage', $obj->getVar('doimage'));
            $mainTab->addElement($image_radio);
        }
        if ($this->isGranted(_PUBLISHER_DOLINEBREAK)) {
            $linebreak_radio =
                new Xoops\Form\RadioYesNo(_CO_PUBLISHER_DOLINEBREAK, 'dolinebreak', $obj->getVar('dobr'));
            $mainTab->addElement($linebreak_radio);
        }
    }


    /**
     * Build the files tab
     *
     * @param PublisherItem      $obj      data source
     * @param ContainerInterface $filesTab add elements to this tab/form
     *
     * @return void
     */
    private function buildFilesTab(PublisherItem $obj, ContainerInterface $filesTab)
    {
        $publisher = Publisher::getInstance();

        // File upload UPLOAD
        if ($this->isGranted(_PUBLISHER_ITEM_UPLOAD_FILE)) {
            // NAME
            $name_text = new Xoops\Form\Text(_CO_PUBLISHER_FILENAME, 'item_file_name', 50, 255, '');
            $name_text->setDescription(_CO_PUBLISHER_FILE_NAME_DSC);
            $filesTab->addElement($name_text);
            unset($name_text);

            // DESCRIPTION
            $description_text =
                new Xoops\Form\TextArea(_CO_PUBLISHER_FILE_DESCRIPTION, 'item_file_description', '');
            $description_text->setDescription(_CO_PUBLISHER_FILE_DESCRIPTION_DSC);
            $filesTab->addElement($description_text);
            unset($description_text);

            //1 - active
            $status_select = new Xoops\Form\RadioYesNo(_CO_PUBLISHER_FILE_STATUS, 'item_file_status', 1);
            $status_select->setDescription(_CO_PUBLISHER_FILE_STATUS_DSC);
            $filesTab->addElement($status_select);
            unset($status_select);

            $file_box = new Xoops\Form\File(_CO_PUBLISHER_ITEM_UPLOAD_FILE, "item_upload_file");
            $file_box->setDescription(_CO_PUBLISHER_ITEM_UPLOAD_FILE_DSC);
            $file_box->set('size', 50);
            $filesTab->addElement($file_box);
            unset($file_box);

            if (!$obj->isNew()) {
                $filesObj = $publisher->getFileHandler()->getAllFiles($obj->getVar('itemid'));
                if (count($filesObj) > 0) {
                    $table = '';
                    $table .= "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
                    $table .= "<tr>";
                    $table .= "<td width='50' class='bg3' align='center'><strong>ID</strong></td>";
                    $table .= "<td width='150' class='bg3' align='left'><strong>"
                        . _AM_PUBLISHER_FILENAME . "</strong></td>";
                    $table .= "<td class='bg3' align='left'><strong>"
                        . _AM_PUBLISHER_DESCRIPTION . "</strong></td>";
                    $table .= "<td width='60' class='bg3' align='center'><strong>"
                        . _AM_PUBLISHER_HITS . "</strong></td>";
                    $table .= "<td width='100' class='bg3' align='center'><strong>"
                        . _AM_PUBLISHER_UPLOADED_DATE . "</strong></td>";
                    $table .= "<td width='60' class='bg3' align='center'><strong>"
                        . _AM_PUBLISHER_ACTION . "</strong></td>";
                    $table .= "</tr>";

                    /* @var $fileObj PublisherFile */
                    foreach ($filesObj as $fileObj) {
                        $modify = "<a href='file.php?op=mod&fileid=" . $fileObj->getVar('fileid')
                            . "'><img src='" . PUBLISHER_URL . "/images/links/edit.gif' title='"
                            . _CO_PUBLISHER_EDITFILE . "' alt='" . _CO_PUBLISHER_EDITFILE . "' /></a>";
                        $delete = "<a href='file.php?op=del&fileid=" . $fileObj->getVar('fileid')
                            . "'><img src='" . PUBLISHER_URL . "/images/links/delete.png' title='"
                            . _CO_PUBLISHER_DELETEFILE . "' alt='" . _CO_PUBLISHER_DELETEFILE . "'/></a>";
                        if ($fileObj->getVar('status') == 0) {
                            $notVisible = "<img src='" . PUBLISHER_URL . "/images/no.gif'/>";
                        } else {
                            $notVisible = '';
                        }
                        $table .= "<tr>";
                        $table .= "<td class='head' align='center'>" . $fileObj->getVar('fileid') . "</td>";
                        $table .= "<td class='odd' align='left'>" . $notVisible . $fileObj->getFileLink() . "</td>";
                        $table .= "<td class='even' align='left'>" . $fileObj->getVar('description') . "</td>";
                        $table .= "<td class='even' align='center'>" . $fileObj->getVar('counter') . "";
                        $table .= "<td class='even' align='center'>" . $fileObj->datesub() . "</td>";
                        $table .= "<td class='even' align='center'> {$modify} {$delete} </td>";
                        $table .= "</tr>";
                    }
                    $table .= "</table>";

                    $files_box = new Xoops\Form\Label(_CO_PUBLISHER_FILES_LINKED, $table);
                    $filesTab->addElement($files_box);
                    unset($files_box, $filesObj, $fileObj);
                }
            }
        }

    }

    /**
     * Build the images tab
     *
     * @param PublisherItem      $obj       data source
     * @param ContainerInterface $imagesTab add elements to this tab/form
     *
     * @return void
     */
    private function buildImagesTab(PublisherItem $obj, ContainerInterface $imagesTab)
    {
        $xoops = Xoops::getInstance();
        $group = $xoops->getUserGroups();

        // IMAGE
        if ($this->isGranted(_PUBLISHER_IMAGE_ITEM)) {
            $imgcat_handler = Images::getInstance()->getHandlerCategories();
            $image_handler = Images::getInstance()->getHandlerImages();

            $objimages = $obj->getImages();
            $mainarray = is_object($objimages['main']) ? array($objimages['main']) : array();
            $mergedimages = array_merge($mainarray, $objimages['others']);
            $objimage_array = array();
            /* @var $imageObj ImagesImage */
            foreach ($mergedimages as $imageObj) {
                $objimage_array[$imageObj->getVar('image_name')] = $imageObj->getVar('image_nicename');
            }

            $catlist = $imgcat_handler->getListByPermission($group, 'imgcat_read', 1);
            $catids = array_keys($catlist);

            $imageObjs = array();
            if (!empty($catids)) {
                $criteria = new CriteriaCompo(new Criteria('imgcat_id', '(' . implode(',', $catids) . ')', 'IN'));
                $criteria->add(new Criteria('image_display', 1));
                $criteria->setSort('image_nicename');
                $criteria->setOrder('ASC');
                $imageObjs = $image_handler->getObjects($criteria, true);
                unset($criteria);
            }
            $image_array = array();
            foreach ($imageObjs as $imageObj) {
                $image_array[$imageObj->getVar('image_name')] = $imageObj->getVar('image_nicename');
            }

            $image_array = array_diff($image_array, $objimage_array);

            $image_select = new Xoops\Form\Select('', 'image_notused', '', 5);
            $image_select->addOptionArray($image_array);
            $image_select->set(
                'onchange',
                'showImgSelected("image_display", "image_notused", "uploads/", "", "'
                . \XoopsBaseConfig::get('url') . '")'
            );
            unset($image_array);

            $image_select2 = new Xoops\Form\Select('', 'image_item', '', 5, true);
            $image_select2->addOptionArray($objimage_array);
            $image_select2->set(
                'onchange',
                'publisher_updateSelectOption("image_item", "image_featured"), '
                . 'showImgSelected("image_display", "image_item", "uploads/", "", "'
                . \XoopsBaseConfig::get('url') . '");'
            );

            $buttonadd = new Xoops\Form\Button('', 'buttonadd', _CO_PUBLISHER_ADD);
            $buttonadd->set(
                'onclick',
                'publisher_appendSelectOption("image_notused", "image_item"), '
                . 'publisher_updateSelectOption("image_item", "image_featured");'
            );

            $buttonremove = new Xoops\Form\Button('', 'buttonremove', _CO_PUBLISHER_REMOVE);
            $buttonremove->set(
                'onclick',
                'publisher_appendSelectOption("image_item", "image_notused"), '
                . 'publisher_updateSelectOption("image_item", "image_featured");'
            );

            $opentable = new Xoops\Form\Label('', "<table><tr><td>");
            $addcol = new Xoops\Form\Label('', "</td><td>");
            $addbreak = new Xoops\Form\Label('', "<br />");
            $closetable = new Xoops\Form\Label('', "</td></tr></table>");

            $xoops->theme()->addScript(PUBLISHER_URL . '/js/ajaxupload.3.9.js');
            //todo, find replacement for error class
            $js_data = new Xoops\Form\Label('', '
<script type= "text/javascript">/*<![CDATA[*/
$(document).ready(function(){
    var button = $("#publisher_upload_button"), interval;
    new AjaxUpload(button,{
        action: "' . PUBLISHER_URL . '/include/ajax_upload.php", // I disabled uploads in this example for security reasons
        responseType: "text/html",
        name: "publisher_upload_file",
        onSubmit : function(file, ext){
            // change button text, when user selects file
            $("#publisher_upload_message").html(" ");
            button.html("<img src=\'' . PUBLISHER_URL . '/images/loadingbar.gif\'/>"); this.setData({
                "image_nicename": $("#image_nicename").val(),
                "imgcat_id" : $("#imgcat_id").val()
            });
            // If you want to allow uploading only 1 file at time,
            // you can disable upload button
            this.disable();
            interval = window.setInterval(function(){
            }, 200);
        },
        onComplete: function(file, response){
            button.text("' . _CO_PUBLISHER_IMAGE_UPLOAD_NEW . '");
            window.clearInterval(interval);
            // enable upload button
            this.enable();
            // add file to the list
            var result = eval(response);
            if (result[0] == "success") {
                 $("#image_item").append("<option value=\'" + result[1] + "\' selected=\'selected\'>" + result[2] + "</option>");
                 publisher_updateSelectOption(\'image_item\', \'image_featured\');
                 showImgSelected(\'image_display\', \'image_item\', \'uploads/\', \'\', \'' . \XoopsBaseConfig::get('url') . '\')
            } else {
                 $("#publisher_upload_message").html("<div class=\'errorMsg\'>" + result[1] + "</div>");
            }
        }
    });
});
/*]]>*/</script>
');
            $messages = new Xoops\Form\Label('', "<div id='publisher_upload_message'></div>");
            $button = new Xoops\Form\Label(
                '',
                "<div id='publisher_upload_button'>" . _CO_PUBLISHER_IMAGE_UPLOAD_NEW . "</div>"
            );
            $nicename = new Xoops\Form\Text('', 'image_nicename', 30, 30, _CO_PUBLISHER_IMAGE_NICENAME);

            $catlist = $imgcat_handler->getListByPermission($group, 'imgcat_read', 1);
            $imagecat = new Xoops\Form\Select('', 'imgcat_id', '', 1);
            $imagecat->addOptionArray($catlist);

            $image_upload_tray = new Xoops\Form\ElementTray(_CO_PUBLISHER_IMAGE_UPLOAD, '');
            $image_upload_tray->addElement($js_data);
            $image_upload_tray->addElement($messages);
            $image_upload_tray->addElement($opentable);

            $image_upload_tray->addElement($imagecat);

            $image_upload_tray->addElement($addbreak);

            $image_upload_tray->addElement($nicename);

            $image_upload_tray->addElement($addbreak);

            $image_upload_tray->addElement($button);

            $image_upload_tray->addElement($closetable);
            $imagesTab->addElement($image_upload_tray);

            $image_tray = new Xoops\Form\ElementTray(_CO_PUBLISHER_IMAGE_ITEMS, '');
            $image_tray->addElement($opentable);

            $image_tray->addElement($image_select);
            $image_tray->addElement($addbreak);
            $image_tray->addElement($buttonadd);

            $image_tray->addElement($addcol);

            $image_tray->addElement($image_select2);
            $image_tray->addElement($addbreak);
            $image_tray->addElement($buttonremove);

            $image_tray->addElement($closetable);
            $image_tray->setDescription(_CO_PUBLISHER_IMAGE_ITEMS_DSC);
            $imagesTab->addElement($image_tray);

            $imagename = is_object($objimages['main']) ? $objimages['main']->getVar('image_name') : '';
            $imageforpath = ($imagename != '') ? $imagename : 'blank.gif';

            $image_select3 = new Xoops\Form\Select(_CO_PUBLISHER_IMAGE_ITEM, 'image_featured', $imagename, 1);
            $image_select3->addOptionArray($objimage_array);
            $image_select3->set(
                'onchange',
                'showImgSelected("image_display", "image_featured", "uploads/", "", "'
                . \XoopsBaseConfig::get('url') . '");'
            );
            $image_select3->setDescription(_CO_PUBLISHER_IMAGE_ITEM_DSC);
            $imagesTab->addElement($image_select3);

            $imgTag = new Xoops\Html\Img([
                'src' => $xoops->url('uploads/' . $imageforpath),
                'width' => 500,
                'name' => 'image_display',
                'id' => 'image_display',
                'alt' => '',

            ]);
            $image_preview = new Xoops\Form\Label(_CO_PUBLISHER_IMAGE_PREVIEW, $imgTag->render());
            $imagesTab->addElement($image_preview);
        }
    }

    /**
     * Build the others tab
     *
     * @param PublisherItem      $obj       data source
     * @param ContainerInterface $othersTab add elements to this tab/form
     *
     * @return void
     */
    private function buildOthersTab(PublisherItem $obj, ContainerInterface $othersTab)
    {
        // Meta Keywords
        if ($this->isGranted(_PUBLISHER_ITEM_META_KEYWORDS)) {
            $text_meta_keywords = new Xoops\Form\TextArea(
                _CO_PUBLISHER_ITEM_META_KEYWORDS,
                'item_meta_keywords',
                $obj->getVar('meta_keywords', 'e'),
                7,
                60
            );
            $text_meta_keywords->setDescription(_CO_PUBLISHER_ITEM_META_KEYWORDS_DSC);
            $othersTab ->addElement($text_meta_keywords);
        }

        // Meta Description
        if ($this->isGranted(_PUBLISHER_ITEM_META_DESCRIPTION)) {
            $text_meta_description = new Xoops\Form\TextArea(
                _CO_PUBLISHER_ITEM_META_DESCRIPTION,
                'item_meta_description',
                $obj->getVar('meta_description', 'e'),
                7,
                60
            );
            $text_meta_description->setDescription(_CO_PUBLISHER_ITEM_META_DESCRIPTION_DSC);
            $othersTab ->addElement($text_meta_description);
        }

        // COMMENTS
        if ($this->isGranted(_PUBLISHER_ALLOWCOMMENTS)) {
            $addcomments_radio = new Xoops\Form\RadioYesNo(
                _CO_PUBLISHER_ALLOWCOMMENTS,
                'allowcomments',
                $obj->getVar('cancomment')
            );
            $othersTab ->addElement($addcomments_radio);
        }

        // WEIGHT
        if ($this->isGranted(_PUBLISHER_WEIGHT)) {
            $othersTab ->addElement(
                new Xoops\Form\Text(_CO_PUBLISHER_WEIGHT, 'weight', 5, 5, $obj->getVar('weight'))
            );
        }
    }

    /**
     * setCheckPermissions
     *
     * @param boolean $checkperm true to check permissions, false to ignore permissions
     *
     * @return void
     */
    public function setCheckPermissions($checkperm)
    {
        $this->checkperm = (bool)$checkperm;
    }

    /**
     * isGranted
     *
     * @param int $item permission item to check
     *
     * @return bool true if permission is granted, false if not
     */
    private function isGranted($item)
    {
        $publisher = Publisher::getInstance();
        $ret = false;
        if (!$this->checkperm || $publisher->getPermissionHandler()->isGranted('form_view', $item)) {
            $ret = true;
        }
        return $ret;
    }

    /**
     * hasTab
     *
     * @param string $tab tab name
     *
     * @return bool true if form has tab named $tab
     */
    private function hasTab($tab)
    {
        if (!isset($tab) || !isset($this->tabs[$tab])) {
            return false;
        }

        $tabRef = $this->tabs[$tab];
        $items = $this->$tabRef;
        foreach ($items as $item) {
            if ($this->isGranted($item)) {
                return true;
            }
        }

        return false;
    }
}

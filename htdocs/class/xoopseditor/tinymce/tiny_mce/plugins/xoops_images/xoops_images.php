<?php
/**
 *  xoops_images plugin for tinymce
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         class / xoopseditor
 * @subpackage      tinymce / xoops plugins
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

use Xoops\Core\Request;

$xoops_root_path = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))));
include_once $xoops_root_path . '/mainfile.php';
defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xoops = Xoops::getInstance();
$xoops->simpleHeader(false);

$helper = Xoops\Module\Helper::getHelper('images');
$helper->loadLanguage('admin');
$helper->loadLanguage('tinymce');
$helper->loadLanguage('main');

$op = Request::getCmd('op', 'list');
$imgcat_id = Request::getInt('imgcat_id', 0);
$start = Request::getInt('start', 0);

$groups = $xoops->isUser() ? $xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);

$xoopsTpl = new XoopsTpl();
switch ($op) {
    case 'list':
    default:
        // Category Select form
        $param = array('imgcat_id' => $imgcat_id, 'target' => null);
        $form = $helper->getForm($param, 'category_imagemanager');
        $xoopsTpl->assign('form_category', $form->render());

        if ($imgcat_id > 0) {
            $imgcount = $helper->getHandlerImages()->countByCategory($imgcat_id);
            $images = $helper->getHandlerImages()->getByCategory($imgcat_id, $start, $helper->getConfig('images_pager'), true);
            $category = $helper->getHandlerCategories()->get($imgcat_id);

            foreach (array_keys($images) as $i) {
                if ($category->getVar('imgcat_storetype') == 'db') {
                    $src = $helper->url("image.php?id=" . $images[$i]->getVar('image_id'));
                } else {
                    $src = XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name');
                }
                $xoopsTpl->append('images', array(
                                          'id' => $images[$i]->getVar('image_id'),
                                          'nicename' => $images[$i]->getVar('image_nicename'),
                                          'mimetype' => $images[$i]->getVar('image_mimetype'),
                                          'src' => $src,
                                          ));
            }
        }
        break;

    case 'upload':
        $category = $helper->getHandlerCategories()->get($imgcat_id);
        if ($imgcat_id > 0 && is_object($category)) {
            $perm_handler = $xoops->getHandlerGroupperm();
            if ($perm_handler->checkRight('imgcat_write', $imgcat_id, $groups)) {
                $xoops->simpleHeader();
                $xoopsTpl = new XoopsTpl();
                $obj =  $helper->getHandlerImages()->create();
                $obj->setVar('imgcat_id', $imgcat_id);
                $form = $helper->getForm(array('obj' => $obj, 'target' => null), 'image_imagemanager');
                $xoopsTpl->assign('form', $form->render());
            }

        }
        break;

    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('xoops_images.php?imgcat_id=' . $imgcat_id, 3, implode('<br />', $xoops->security()->getErrors()));
        }

        $mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
        $msg[] = _AM_IMAGES_IMG_SAVE;

        $category = $helper->getHandlerCategories()->get($imgcat_id);
        $image_id = Request::getInt('image_id', 0);
        $obj = $helper->getHandlerImages()->create();

        $obj->setVar('image_nicename', Request::getString('image_nicename', ''));
        $obj->setVar('image_created', time());
        $obj->setVar('image_display', Request::getInt('image_display', 1));
        $obj->setVar('image_weight', Request::getInt('image_weight', 0));
        $obj->setVar('imgcat_id', $imgcat_id);

        $xoops_upload_file = Request::getArray('xoops_upload_file', array());

        $uploader = new XoopsMediaUploader(
            XOOPS_UPLOAD_PATH . '/images',
            $mimetypes,
            $category->getVar('imgcat_maxsize'),
            $category->getVar('imgcat_maxwidth'),
            $category->getVar('imgcat_maxheight')
        );
        if ($uploader->fetchMedia($xoops_upload_file[0])) {
            $uploader->setPrefix("img");
            if (!$uploader->upload()) {
                $msg[] = $uploader->getErrors();
                $obj->setVar('image_name', 'blank.gif');
                $obj->setVar('image_mimetype', 'image/gif');
            } else {
                $obj->setVar('image_mimetype', $uploader->getMediaType());
                if ($category->getVar('imgcat_storetype') == 'db') {
                    $fp = @fopen($uploader->getSavedDestination(), 'rb');
                    $fbinary = @fread($fp, filesize($uploader->getSavedDestination()));
                    @fclose($fp);
                    $image_body = $fbinary;
                } else {
                    $obj->setVar('image_name', 'images/' . $uploader->getSavedFileName());
                }
            }
        }

        if ($image_id = $helper->getHandlerImages()->insert($obj)) {
            if ($category->getVar('imgcat_storetype') == 'db') {
                $imagebody = $helper->getHandlerImagesBody()->get($image_id);
                if (!is_object($imagebody)) {
                    $imagebody = $helper->getHandlerImagesBody()->create();
                    $imagebody->setVar('image_id', $image_id);
                }
                $imagebody->setVar('image_body', $image_body);
                if ($helper->getHandlerImagesBody()->insert($imagebody)) {
                    @unlink($uploader->getSavedDestination());
                }
            }
            $xoops->redirect('xoops_images.php?imgcat_id=' . $imgcat_id, 2, implode('<br />', $msg));
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        break;
}
$xoopsTpl->display('module:images/images_tinymce.tpl');
$xoops->simpleFooter();

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
 * Module Images
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Images
 * @since           2.6.0
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$helper = Images::getInstance();
$helper->loadLanguage('main');
$helper->loadLanguage('admin');

$op = Request::getCmd('op', 'list');
$target = Request::getWord('target', '');
$imgcat_id = Request::getInt('imgcat_id', 0);
$start = Request::getInt('start', 0);

if (empty($target)) {
    exit('Target not set');
}

$groups = $xoops->isUser() ? $xoops->user->getGroups() : array(XOOPS_GROUP_ANONYMOUS);

$xoops->simpleHeader();
$xoopsTpl = new XoopsTpl();
$xoopsTpl->assign('sitename', htmlspecialchars($xoops->getConfig('sitename'), ENT_QUOTES));
$xoopsTpl->assign('target', htmlspecialchars($target, ENT_QUOTES));

switch ($op) {
    case 'list':
    default:
        // Category Select form
        $param = array('imgcat_id' => $imgcat_id, 'target' => $target);
        $form = $helper->getForm($param, 'category_imagemanager');
        $xoopsTpl->assign('form_category', $form->render());

        if ($imgcat_id > 0) {
            $imgcount = $helper->getHandlerImages()->countByCategory($imgcat_id);
            $images = $helper->getHandlerImages()->getByCategory($imgcat_id, $start, $helper->getConfig('images_pager'), true);
            $category = $helper->getHandlerCategories()->get($imgcat_id);

            foreach (array_keys($images) as $i) {
                if ($category->getVar('imgcat_storetype') == 'db') {
                    $lcode = '[img align=left id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                    $code = '[img align=center id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                    $rcode = '[img align=right id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                    $src = $helper->url("image.php?id=" . $images[$i]->getVar('image_id'));
                } else {
                    $lcode = '[img align=left]' . XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name') . '[/img]';
                    $code = '[img align=center]' . XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name') . '[/img]';
                    $rcode = '[img align=right]' . XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name') . '[/img]';
                    $src = XOOPS_UPLOAD_URL . '/' . $images[$i]->getVar('image_name');
                }
                $xoopsTpl->append('images', array(
                    'id' => $images[$i]->getVar('image_id'),
                    'nicename' => $images[$i]->getVar('image_nicename'),
                    'mimetype' => $images[$i]->getVar('image_mimetype'),
                    'src' => $src,
                    'lxcode' => $lcode,
                    'xcode' => $code,
                    'rxcode' => $rcode
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
                $form = $helper->getForm(array('obj' => $obj, 'target' => $target), 'image_imagemanager');
                $xoopsTpl->assign('form', $form->render());
            }
        }
        break;

    case 'save':
        if (!$xoops->security()->check()) {
            $xoops->redirect('imagemanager.php?imgcat_id=' . $imgcat_id, 3, implode('<br />', $xoops->security()->getErrors()));
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

        $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH . '/images', $mimetypes,
                    $category->getVar('imgcat_maxsize'), $category->getVar('imgcat_maxwidth'), $category->getVar('imgcat_maxheight'));
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

        if ( $image_id = $helper->getHandlerImages()->insert($obj)) {
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
            $xoops->redirect('imagemanager.php?target=' . $target . '&imgcat_id=' . $imgcat_id, 2, implode('<br />', $msg));
        }
        echo $xoops->alert('error', $obj->getHtmlErrors());
        break;
}
$xoopsTpl->assign('xsize', 800);
$xoopsTpl->assign('ysize', 600);
$xoopsTpl->display('module:images/images_imagemanager.tpl');
$xoops->simpleFooter();

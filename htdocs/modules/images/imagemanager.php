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
 * Module Images
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         Images
 * @since           2.6.0
 * @version         $Id$
 */

include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$helper = Images::getInstance();
$helper->loadLanguage('main');
$helper->loadLanguage('admin');
$request = Xoops_Request::getInstance();

$op = $request->asStr('op', 'list');
$target = XoopsFilterInput::clean($_REQUEST['target'], 'WORD'); //$request->asStr('target', '');
$imgcat_id = $request->asInt('imgcat_id', 0);
$start = $request->asInt('start', 0);

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
        $image_id = $request->asInt('image_id', 0);
        $obj = $helper->getHandlerImages()->create();

        $obj->setVar('image_nicename', $request->asStr('image_nicename', ''));
        $obj->setVar('image_created', time());
        $obj->setVar('image_display', $request->asInt('image_display', 1));
        $obj->setVar('image_weight', $request->asInt('image_weight', 0));
        $obj->setVar('imgcat_id', $imgcat_id);

        $xoops_upload_file = $request->asArray('xoops_upload_file', array());

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
$xoopsTpl->display('module:images|images_imagemanager.html');
$xoops->simpleFooter();

/*
if ($op == 'upload') {
    $imgcat_id = intval($_GET['imgcat_id']);
    $imgcat = $helper->getHandlerCategories()->get($imgcat_id);
    $error = false;
    if (!is_object($imgcat)) {
        $error = true;
    } else {
        $imgcatperm_handler = $xoops->getHandlerGroupperm();
        if ($xoops->isUser()) {
            if (!$imgcatperm_handler->checkRight('imgcat_write', $imgcat_id, $xoops->user->getGroups())) {
                $error = true;
            }
        } else {
            if (!$imgcatperm_handler->checkRight('imgcat_write', $imgcat_id, XOOPS_GROUP_ANONYMOUS)) {
                $error = true;
            }
        }
    }
    if ($error != false) {
        $xoops->simpleHeader(false);
        echo '</head><body><div style="text-align:center;"><input value="' . _BACK . '" type="button" onclick="javascript:history.go(-1);" /></div>';
        $xoops->simpleFooter();
    }
    $xoopsTpl = new XoopsTpl();
    $xoopsTpl->assign('show_cat', $imgcat_id);
    $xoopsTpl->assign('lang_imgmanager', _IMGMANAGER);
    $xoopsTpl->assign('sitename', htmlspecialchars($xoops->getConfig('sitename'), ENT_QUOTES));
    $xoopsTpl->assign('target', htmlspecialchars($_GET['target'], ENT_QUOTES));
    $form = new XoopsThemeForm('', 'image_form', 'imagemanager.php', 'post', true);
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new XoopsFormText(_IMAGENAME, 'image_nicename', 20, 255), true);
    $form->addElement(new XoopsFormLabel(_IMAGECAT, $imgcat->getVar('imgcat_name')));
    $form->addElement(new XoopsFormFile(_IMAGEFILE, 'image_file', $imgcat->getVar('imgcat_maxsize')), true);
    $form->addElement(new XoopsFormLabel(_IMGMAXSIZE, $imgcat->getVar('imgcat_maxsize')));
    $form->addElement(new XoopsFormLabel(_IMGMAXWIDTH, $imgcat->getVar('imgcat_maxwidth')));
    $form->addElement(new XoopsFormLabel(_IMGMAXHEIGHT, $imgcat->getVar('imgcat_maxheight')));
    $form->addElement(new XoopsFormHidden('imgcat_id', $imgcat_id));
    $form->addElement(new XoopsFormHidden('op', 'doupload'));
    $form->addElement(new XoopsFormHidden('target', $target));
    $form->addElement(new XoopsFormButton('', 'img_button', _SUBMIT, 'submit'));
    $form->assign($xoopsTpl);
    $xoopsTpl->assign('lang_close', _CLOSE);
    $xoopsTpl->display('module:images|images_imagemanager2.html');
    exit();
}
*/
/*
if ($op == 'doupload') {
    if ($xoops->security()->check()) {
        $image_nicename = isset($_POST['image_nicename']) ? $_POST['image_nicename'] : '';
        $xoops_upload_file = isset($_POST['xoops_upload_file']) ? $_POST['xoops_upload_file'] : array();
        $imgcat_id = isset($_POST['imgcat_id']) ? intval($_POST['imgcat_id']) : 0;
        $imgcat = $helper->getHandlerCategories()->get($imgcat_id);
        $error = false;
        if (!is_object($imgcat)) {
            $error = true;
        } else {
            $imgcatperm_handler = $xoops->getHandlerGroupperm();
            if ($xoops->isUser()) {
                if (!$imgcatperm_handler->checkRight('imgcat_write', $imgcat_id, $xoops->user->getGroups())) {
                    $error = true;
                }
            } else {
                if (!$imgcatperm_handler->checkRight('imgcat_write', $imgcat_id, XOOPS_GROUP_ANONYMOUS)) {
                    $error = true;
                }
            }
        }
    } else {
        $error = true;
    }
    if ($error != false) {
        $xoops->simpleHeader(false);
        echo '</head><body><div style="text-align:center;">' . implode('<br />', $xoops->security()->getErrors()) . '<br /><input value="' . _BACK . '" type="button" onclick="javascript:history.go(-1);" /></div>';
        $xoops->simpleFooter();
    }
    $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array(
            'image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'
        ), $imgcat->getVar('imgcat_maxsize'), $imgcat->getVar('imgcat_maxwidth'), $imgcat->getVar('imgcat_maxheight'));
    $uploader->setPrefix('img');
    if ($uploader->fetchMedia($xoops_upload_file[0])) {
        if (!$uploader->upload()) {
            $err = $uploader->getErrors();
        } else {
            $image_handler = $xoops->getHandlerImage();;
            $image = $image_handler->create();
            $image->setVar('image_name', $uploader->getSavedFileName());
            $image->setVar('image_nicename', $image_nicename);
            $image->setVar('image_mimetype', $uploader->getMediaType());
            $image->setVar('image_created', time());
            $image->setVar('image_display', 1);
            $image->setVar('image_weight', 0);
            $image->setVar('imgcat_id', $imgcat_id);
            if ($imgcat->getVar('imgcat_storetype') == 'db') {
                $fp = @fopen($uploader->getSavedDestination(), 'rb');
                $fbinary = @fread($fp, filesize($uploader->getSavedDestination()));
                @fclose($fp);
                $image->setVar('image_body', $fbinary, true);
                @unlink($uploader->getSavedDestination());
            }
            if (!$image_handler->insertImage($image)) {
                $err = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
            }
        }
    } else {
        $err = sprintf(_FAILFETCHIMG, 0);
        $err .= '<br />' . implode('<br />', $uploader->getErrors(false));
    }
    if (isset($err)) {
        $xoops->simpleHeader(false);
        echo $xoops->alert('error', $err);
        echo '</head><body><div style="text-align:center;"><input value="' . _BACK . '" type="button" onclick="javascript:history.go(-1);" /></div>';
        $xoops->simpleFooter();
    }
    header('location: imagemanager.php?cat_id=' . $imgcat_id . '&target=' . $target);
}
*/

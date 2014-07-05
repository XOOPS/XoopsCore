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
 * @version         $Id$
 */

include_once __DIR__ . '/admin_header.php';

$xoops = Xoops::getInstance();
$errors = array();

if (publisher_pagewrap_upload($errors)) {
    $xoops->redirect($_POST['backto'], 2, _AM_PUBLISHER_FILEUPLOAD_SUCCESS);
} else {
    $errorstxt = implode('<br />', $errors);
    $message = sprintf(_CO_PUBLISHER_MESSAGE_FILE_ERROR, $errorstxt);
    $xoops->redirect($_POST['backto'], 5, $message);
}

function publisher_pagewrap_upload(&$errors)
{

    $publisher = Publisher::getInstance();
    $post_field = 'fileupload';

    $max_size = $publisher->getConfig('maximum_filesize');
    $max_imgwidth = $publisher->getConfig('maximum_image_width');
    $max_imgheight = $publisher->getConfig('maximum_image_height');

    if (!is_dir(PublisherUtils::getUploadDir(true, 'content'))) {
        mkdir(PublisherUtils::getUploadDir(true, 'content'), 0757);
    }
    $allowed_mimetypes = array('text/html', 'text/plain', 'application/xhtml+xml');
    $uploader = new XoopsMediaUploader(PublisherUtils::getUploadDir(true, 'content') . '/', $allowed_mimetypes, $max_size, $max_imgwidth, $max_imgheight);
    if ($uploader->fetchMedia($post_field)) {
        $uploader->setTargetFileName($uploader->getMediaName());
        if ($uploader->upload()) {
            return true;
        } else {
            $errors = array_merge($errors, $uploader->getErrors(false));
            return false;
        }

    } else {
        $errors = array_merge($errors, $uploader->getErrors(false));
        return false;
    }
}

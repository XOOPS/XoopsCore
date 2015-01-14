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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Images
 * @since           2.6.0
 * @version         $Id$
 */

include dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';
$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$helper = Xoops\Module\Helper::getHelper('images');
$request = Xoops_Request::getInstance();

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    set_magic_quotes_runtime(0);
}

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}

$image_id = $request->asInt('id', 0);
if (empty($image_id)) {
    header('Content-type: image/gif');
    readfile(XOOPS_ROOT_PATH . '/uploads/blank.gif');
    exit();
}

$image = $helper->getHandlerImages()->getById($image_id);

if (count($image) != 0) {
    header('Content-type: ' . $image[$image_id]->getVar('image_mimetype'));
    header('Cache-control: max-age=31536000');
    header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . 'GMT');
    header('Content-disposition: filename=' . $image[$image_id]->getVar('image_name'));
    header('Content-Length: ' . strlen($image[$image_id]->getVar('image_body')));
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $image[$image_id]->getVar('image_created')) . 'GMT');
    echo $image[$image_id]->getVar('image_body');
} else {
    header('Content-type: image/gif');
    readfile(XOOPS_UPLOAD_PATH . '/blank.gif');
}

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
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */

// this is located in include, otherwise normal/anon users do not have authority to run
include dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

$xoops = Xoops::getinstance();
$xoops->logger()->quiet();

$imgPath   = Request::getString('img', '');
$imgWidth  = Request::getInt('w', 0);
$imgHeight = Request::getInt('h', 0);

if ($imgWidth==0 && $imgHeight==0) {
    $configs = $xoops->getModuleConfigs('thumbs');
    $imgWidth  = $configs['thumbs_width'];
    $imgHeight = $configs['thumbs_height'];
}
$helper  = $xoops->getModuleHelper('thumbs');
$thumbPath = $helper->buildThumbPath($imgPath, $imgWidth, $imgHeight);

$oldUmask = umask(022);
mkdir(dirname($xoops->path($thumbPath)), 0755, true);
umask($oldUmask);

$image = new Zebra_Image();

$image->source_path = $xoops->path($imgPath);
$image->target_path = $xoops->path($thumbPath);

$image->preserve_aspect_ratio = true;
$image->enlarge_smaller_images = false;
$image->preserve_time = true;

if ($image->resize($imgWidth, $imgHeight, ZEBRA_IMAGE_NOT_BOXED, -1)) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $xoops->url($thumbPath));
} else {
    header("HTTP/1.0 404 Not Found");
    // http_response_code(400);
    // exit("Parameter error");
    switch ($image->error) {
        case 1:
            echo 'Source file could not be found!';
            break;
        case 2:
            echo 'Source file is not readable!';
            break;
        case 3:
            echo 'Could not write target file!';
            break;
        case 4:
            echo 'Unsupported source file format!';
            break;
        case 5:
            echo 'Unsupported target file format!';
            break;
        case 6:
            echo 'GD library version does not support target file format!';
            break;
        case 7:
            echo 'GD library is not installed!';
            break;
        case 8:
            echo '"chmod" command is disabled via configuration!';
            break;
    }
}
exit();

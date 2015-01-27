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
 * XOOPS restricted file access
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.4.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 */

$xoopsOption['nocommon'] = true;
require_once __DIR__ . '/mainfile.php';

//error_reporting(0);

//require_once XOOPS_ROOT_PATH . '/class/xoopsload.php';

$xoops = Xoops::getInstance();
//$xoops->pathTranslation(); // alread run in Xoops __construct

// Fetch path from query string if path is not set, i.e. through a direct request
if (!isset($path)) {
    if (!empty($_SERVER['QUERY_STRING'])) {
        $path = $_SERVER['QUERY_STRING'];
        $path = (substr($path, 0, 1) == '/') ? substr($path, 1) : $path;
    } else {
        header("HTTP/1.0 404 Not Found");
        exit();
    }
}

$path_type = substr($path, 0, strpos($path, '/'));
if (!isset($xoops->paths[$path_type])) {
    $path = "XOOPS/" . $path;
    $path_type = "XOOPS";
}

//We are not allowing output of xoops_data
if ($path_type == 'var') {
    header("HTTP/1.0 404 Not Found");
    exit();
}

$file = realpath($xoops->path($path));
$dir = realpath($xoops->paths[$path_type][0]);

//We are not allowing directory traversal either
if ($file===false || $dir===false || !strstr($file, $dir)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

//We can't output empty files and php files do not output
if (empty($file) || strpos($file, '.php') !== false) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

//$file = $xoops->path($path);
$mtime = filemtime($file);

// Is there really a file to output?
if ($mtime === false) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
    if (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $mtime) {
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}

$path_parts = pathinfo($file);
$ext = (isset($path_parts['extension'])) ? $path_parts['extension'] : '';
$mimetype = \Xoops\Core\MimeTypes::findType($ext);
//Do not output garbage
if (empty($mimetype)) {
    header("HTTP/1.0 404 Not Found");
    exit();
}

// Output now
// seconds, minutes, hours, days
$expires = 60*60*24*15;
//header("Pragma: public");
header("Cache-Control: public, max-age=" . $expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s T', $mtime));
header('Content-type: ' . $mimetype);
readfile($file);
exit;

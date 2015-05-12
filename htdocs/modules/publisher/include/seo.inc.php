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
 * @author          Sudhaker Raj <http://xoops.biz>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

$publisher = Publisher::getInstance();

$seoOp = @$_GET['seoOp'];
$seoArg = @$_GET['seoArg'];

if (empty($seoOp) && @$_SERVER['PATH_INFO']) {

    // SEO mode is path-info
    /*
    Sample URL for path-info
    http://localhost/modules/publisher/seo.php/item.2/can-i-turn-the-ads-off.html
    */
    $data = explode("/", $_SERVER['PATH_INFO']);

    $seoParts = explode('.', $data[1]);
    $seoOp = $seoParts[0];
    $seoArg = $seoParts[1];
    // for multi-argument modules, where itemid and catid both are required.
    // $seoArg = substr($data[1], strlen($seoOp) + 1);

}

$seoMap = array(
    'category' => 'category.php',
    'item' => 'item.php',
    'print' => 'print.php'
);

if (!empty($seoOp) && isset($seoMap[$seoOp])) {
    // module specific dispatching logic, other module must implement as
    // per their requirements.

    $url_arr = explode('/modules/', $_SERVER['PHP_SELF']);
    $newUrl = $url_arr[0] . '/modules/' . PUBLISHER_DIRNAME . '/' . $seoMap[$seoOp];

    $_ENV['PHP_SELF'] = $newUrl;
    $_SERVER['SCRIPT_NAME'] = $newUrl;
    $_SERVER['PHP_SELF'] = $newUrl;
    switch ($seoOp) {
        case 'category':
            $_SERVER['REQUEST_URI'] = $newUrl . '?categoryid=' . $seoArg;
            $_GET['categoryid'] = $seoArg;
            $_REQUEST['categoryid'] = $seoArg;
            break;
        case 'item':
        case 'print':
        default:
            $_SERVER['REQUEST_URI'] = $newUrl . '?itemid=' . $seoArg;
            $_GET['itemid'] = $seoArg;
            $_REQUEST['itemid'] = $seoArg;
    }
    include $publisher->path($seoMap[$seoOp]);
    exit;
}

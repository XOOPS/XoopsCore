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
 * Protector
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';
$xoops->db();
global $xoopsDB;
$db = $xoopsDB;

// Call header
$xoops->header('admin:protector/protector_advisory.html');

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('advisory.php');

// Define scripts
$xoops->theme()->addScript('modules/system/js/admin.js');

$i = 0;
// XOOPS_ROOT_PATH
// calculate the relative path between XOOPS_ROOT_PATH and XOOPS_TRUST_PATH
$root_paths = explode('/', XOOPS_ROOT_PATH);
$trust_paths = explode('/', XOOPS_TRUST_PATH);
foreach ($root_paths as $i => $rpath) {
    if ($rpath != $trust_paths[$i]) {
        break;
    }
}
$relative_path = str_repeat('../', count($root_paths) - $i) . implode('/', array_slice($trust_paths, $i));
$security_arr[$i]['id'] = $i + 1;
$security_arr[$i]['type'] = 'XOOPS_TRUST_PATH';
$security_arr[$i]['status'] = '-';
$security_arr[$i]['info'] = "<img src='" . XOOPS_URL . '/' . htmlspecialchars($relative_path) . "/modules/protector/public_check.png' width='40' height='20' alt='' style='border:1px solid black;' /> <a href='" . XOOPS_URL . '/' . htmlspecialchars($relative_path) . "/modules/protector/public_check.php'>" . _AM_ADV_TRUSTPATHPUBLICLINK . "</a>";
$security_arr[$i]['text'] = _AM_ADV_TRUSTPATHPUBLIC;
$i++;

// register_globals
$safe = !ini_get("register_globals");
$security_arr[$i]['id'] = $i + 1;
$security_arr[$i]['type'] = 'register_globals';
if ($safe) {
    $security_arr[$i]['status'] = '1';
    $security_arr[$i]['info'] = "<span style='color:green;font-weight:bold;'>register_globals: off</span>";
} else {
    $security_arr[$i]['status'] = '0';
    $security_arr[$i]['info'] = "<span style='color:red;font-weight:bold;'>register_globals: on</span>";
}
$security_arr[$i]['text'] = _AM_ADV_REGISTERGLOBALS . "<br /><br />" . XOOPS_ROOT_PATH . "/.htaccess<br /><br />" . _AM_ADV_REGISTERGLOBALS2 . "<br /><br /><b>php_flag &nbsp; register_globals &nbsp; off</b>";
$i++;

// allow_url_fopen
$safe = !ini_get("allow_url_fopen");
$security_arr[$i]['id'] = $i + 1;
$security_arr[$i]['type'] = 'allow_url_fopen';
if ($safe) {
    $security_arr[$i]['status'] = '1';
    $security_arr[$i]['info'] = "<span style='color:green;font-weight:bold;'>allow_url_fopen: off</span>";
} else {
    $security_arr[$i]['status'] = '0';
    $security_arr[$i]['info'] = "<span style='color:red;font-weight:bold;'>allow_url_fopen: on</span>";
}
$security_arr[$i]['text'] = _AM_ADV_ALLOWURLFOPEN;
$i++;

// session.use_trans_sid
$safe = !ini_get("session.use_trans_sid");
$security_arr[$i]['id'] = $i + 1;
$security_arr[$i]['type'] = 'session.use_trans_sid';
if ($safe) {
    $security_arr[$i]['status'] = '1';
    $security_arr[$i]['info'] = "<span style='color:green;font-weight:bold;'>session.use_trans_sid: off</span>";
} else {
    $security_arr[$i]['status'] = '0';
    $security_arr[$i]['info'] = "<span style='color:red;font-weight:bold;'>session.use_trans_sid: on</span>";
}
$security_arr[$i]['text'] = _AM_ADV_USETRANSSID;
$i++;

// XOOPS_DB_PREFIX
$safe = strtolower(XOOPS_DB_PREFIX) != 'xoops';
$security_arr[$i]['id'] = $i + 1;
$security_arr[$i]['type'] = 'XOOPS_DB_PREFIX';
if ($safe) {
    $security_arr[$i]['status'] = '1';
    $security_arr[$i]['info'] = "<span style='color:green;font-weight:bold;'>XOOPS_DB_PREFIX: " . XOOPS_DB_PREFIX . "</span>";
} else {
    $security_arr[$i]['status'] = '0';
    $security_arr[$i]['info'] = "<span style='color:red;font-weight:bold;'>XOOPS_DB_PREFIX: " . XOOPS_DB_PREFIX . "</span>&nbsp;<a href='prefix_manager.php'>" . _AM_ADV_LINK_TO_PREFIXMAN . "</a>";
}
$security_arr[$i]['text'] = _AM_ADV_DBPREFIX;
$i++;

// patch to mainfile.php
$security_arr[$i]['id'] = $i + 1;
$security_arr[$i]['type'] = 'mainfile.php';
if (!defined('PROTECTOR_PRECHECK_INCLUDED')) {
    $security_arr[$i]['status'] = '0';
    $security_arr[$i]['info'] = "<span style='color:red;font-weight:bold;'>missing precheck</span>";
} else {
    if (!defined('PROTECTOR_POSTCHECK_INCLUDED')) {
        $security_arr[$i]['status'] = '0';
        $security_arr[$i]['info'] = "<span style='color:red;font-weight:bold;'>missing postcheck</span>";
    } else {
        $security_arr[$i]['status'] = '1';
        $security_arr[$i]['info'] = "<span style='color:green;font-weight:bold;'>patched</span>";
    }

}
$security_arr[$i]['text'] = _AM_ADV_MAINUNPATCHED;
$i++;

// databasefactory.php
$security_arr[$i]['id'] = $i + 1;
$security_arr[$i]['type'] = 'databasefactory.php';
if (substr(@XOOPS_VERSION, 6, 3) < 2.4 && strtolower(get_class($db)) != 'protectormysqldatabase') {
    $security_arr[$i]['status'] = '0';
    $security_arr[$i]['info'] = "<span style='color:red;font-weight:bold;'>" . _AM_ADV_DBFACTORYUNPATCHED . "</span>";
} else {
    $security_arr[$i]['status'] = '1';
    $security_arr[$i]['info'] = "<span style='color:green;font-weight:bold;'>" . _AM_ADV_DBFACTORYPATCHED . "</span>";
}
$security_arr[$i]['text'] = '';
$i++;

foreach (array_keys($security_arr) as $i) {
    $xoops->tpl()->appendByRef('security', $security_arr[$i]);
    $xoops->tpl()->appendByRef('popup_security', $security_arr[$i]);
}

// Check contaminations
$uri_contami = XOOPS_URL . "/index.php?xoopsConfig%5Bnocommon%5D=1";
$xoops->tpl()->assign('uri_contami', $uri_contami);

// Check isolated comments
$uri_isocom = XOOPS_URL . "/index.php?cid=" . urlencode(",password /*");
$xoops->tpl()->assign('uri_isocom', $uri_isocom);

$xoops->footer();

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
 * Installer common include file
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id$
 **/

/**
 * If non-empty, only this user can access this installer
 */
define('INSTALL_USER', '');
define('INSTALL_PASSWORD', '');
define('XOOPS_INSTALL', 1);
define('XOOPS_INSTALL_PATH', dirname(__DIR__));

// options for mainfile.php
if (false === date_default_timezone_set(@date_default_timezone_get())) {
    date_default_timezone_set('UTC'); // use this until properly set
}
if (empty($xoopsOption['hascommon'])) {
    $xoopsOption['nocommon'] = true;
    session_start();
}
$mainfile = dirname(dirname(__DIR__)) . '/mainfile.php';
if (file_exists($mainfile)) {
    include $mainfile;
}
if (!defined("XOOPS_ROOT_PATH")) {
    define("XOOPS_ROOT_PATH", str_replace("\\", "/", realpath('../')));
    define("XOOPS_PATH", isset($_SESSION['settings']['PATH']) ? $_SESSION['settings']['PATH']:"");
    define("XOOPS_VAR_PATH", isset($_SESSION['settings']['VAR_PATH']) ? $_SESSION['settings']['VAR_PATH']:"");
    define("XOOPS_URL", isset($_SESSION['settings']['URL']) ? $_SESSION['settings']['URL']:"");
}

include XOOPS_INSTALL_PATH . '/class/installwizard.php';
include_once XOOPS_ROOT_PATH . '/include/version.php';
include_once XOOPS_INSTALL_PATH . '/include/functions.php';
include_once XOOPS_ROOT_PATH . '/include/defines.php';
//include_once XOOPS_ROOT_PATH . '/class/xoopsload.php';
if (!class_exists('XoopsBaseConfig', false)) {
    include_once XOOPS_ROOT_PATH . '/class/XoopsBaseConfig.php';
    XoopsBaseConfig::bootstrapTransition();
}
$_SESSION['pageHasHelp'] = false;
$_SESSION['pageHasForm'] = false;

$wizard = new XoopsInstallWizard();
$_SESSION['wizard'] = $wizard;

if (!$wizard->xoInit()) {
    exit('Init Error');
}

if (!isset($_SESSION['settings']) || !is_array($_SESSION['settings'])) {
    $_SESSION['settings'] = array();
}

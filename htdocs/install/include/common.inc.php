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

if (!class_exists('XoopsBaseConfig', false)) {
    require_once __DIR__ . '/../../class/XoopsBaseConfig.php';
    XoopsBaseConfig::bootstrapTransition();
}

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

include \XoopsBaseConfig::get('install-path') . '/class/installwizard.php';
include_once \XoopsBaseConfig::get('root-path') . '/include/version.php';
include_once \XoopsBaseConfig::get('install-path') . '/include/functions.php';
include_once \XoopsBaseConfig::get('root-path') . '/include/defines.php';

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

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
 * Installer db inserting page
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
 */

require_once dirname(__FILE__) . '/include/common.inc.php';


$xoops = Xoops::getInstance();

/* @var $wizard XoopsInstallWizard */
$wizard = $_SESSION['wizard'];

$settings = $_SESSION['settings'];

$language = $wizard->language;
$xoops->setConfig('locale', $language);
$xoops->loadLocale();

$dbm = new XoopsDatabaseManager();

if (!$dbm->isConnectable()) {
    $wizard->redirectToPage('dbsettings');
    exit();
}
$res = $dbm->query("SELECT COUNT(*) FROM " . $dbm->db->prefix("users"));
if (!$res) {
    $wizard->redirectToPage('dbsettings');
    exit();
}

list ($count) = $dbm->db->fetchRow( $res );
$process = $count ? '' : 'insert';
$update = false;

$siteconfig = $_SESSION['siteconfig'];
$adminname = $siteconfig['adminname'];
$adminpass = $siteconfig['adminpass'];
$adminmail = $siteconfig['adminmail'];


if ($process) {
    //$cm = 'dummy';
    $wizard->loadLangFile('install2');

    $temp = md5($adminpass);
    $regdate = time();
    $dbm->insert('users', " VALUES (1,'','" . addslashes($adminname) . "','" . addslashes($adminmail) . "','" . XOOPS_URL . "/','avatars/blank.gif','" . $regdate . "','','','',1,'','','','','" . $temp . "',0,0,7,5,'default','0.0'," . time() . ",'flat',0,1,0,'','','',0)");
    $content = '<div class="x2-note successMsg">' . DATA_INSERTED . "</div><br />" . $dbm->report();
} else if ($update) {
    $sql = "UPDATE " . $dbm->db->prefix("users") . " SET `uname` = '" . addslashes($adminname) . "', `email` = '" . addslashes($adminmail) . "', `user_regdate` = '" . time() . "', `pass` = '" . md5($adminpass) . "', `last_login` = '" . time() . "' WHERE uid = 1";
    $dbm->db->queryF($sql);
    $content = '';
} else {
    $content = "<div class='x2-note confirmMsg'>" . DATA_ALREADY_INSERTED . "</div>";
}

setcookie('xo_install_user', '', null, null, null);
if (isset( $settings['authorized'] ) && !empty($adminname) && !empty($adminpass)) {
    setcookie('xo_install_user', addslashes($adminname) . '-' . md5(md5($adminpass) . XOOPS_DB_NAME . XOOPS_DB_PASS . XOOPS_DB_PREFIX), null, null, null);
}

$_SESSION['pageHasHelp'] = false;
$_SESSION['pageHasForm'] = false;
$_SESSION['content'] = $content;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';
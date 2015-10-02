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
 * Installer introduction page
 *
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     installer
 * @since       2.3.0
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @author      Kazumi Ono <webmaster@myweb.ne.jp>
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @author      DuGris (aka L. JEN) <dugris@frxoops.org>
 * @version     $Id$
 */

require_once __DIR__ . '/include/common.inc.php';

$_SESSION['error'] = array();
//$_SESSION['settings'] = array();
$_SESSION['siteconfig'] = array(
    'adminname'  => '',
    'adminmail'  => '',
    'adminpass'  => '',
    'adminpass2' => '',
);

/* @var $wizard XoopsInstallWizard */
$wizard = $_SESSION['wizard'];

$wizard->loadLangFile('welcome');
$content = $_SESSION['content'];

$writable = "<ul class='confirmMsg'>";
foreach ($wizard->configs['writable'] as $key => $value) {
    if (is_dir('../' . $value)) {
        $writable .= "<li class='directory'>$value</li>";
    } else {
        $writable .= "<li class='files'>$value</li>";
    }
}
$writable .= "</ul>";

$xoops_trust = "<ul class='confirmMsg'>";
foreach ($wizard->configs['xoopsPathDefault'] as $key => $value) {
    $xoops_trust .= "<li class='directory'>$value</li>";
}
$xoops_trust .= "</ul>";

$writable_trust = "<ul class='confirmMsg'>";
foreach ($wizard->configs['dataPath'] as $key => $value) {
    $writable_trust .= "<li class='directory'>" . $wizard->configs['xoopsPathDefault']['data'] . '/' . $key . "</li>";
    if (is_array($value)) {
        foreach ($value as $key2 => $value2) {
            $writable_trust .= "<li class='directory'>" . $wizard->configs['xoopsPathDefault']['data'] . '/' . $key . '/' . $value2 . "</li>";
        }
    }
}
$writable_trust .= "</ul>";

$content = sprintf($content, $writable, $xoops_trust, $writable_trust);

$_SESSION['pageHasHelp'] = false;
$_SESSION['pageHasForm'] = false;
$_SESSION['content'] = $content;
include XOOPS_INSTALL_PATH . '/include/install_tpl.php';

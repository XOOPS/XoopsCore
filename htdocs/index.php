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
 * XOOPS global entry
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.0.0
 * @author          Kazumi Ono <webmaster@myweb.ne.jp>
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

$mainfile = __DIR__ . '/mainfile.php';
if (file_exists($mainfile)) {
    include $mainfile;
} elseif (file_exists(__DIR__ . '/install/index.php')) {
    header('Location: install/index.php');
    exit;
}


$xoops = Xoops::getInstance();
$xoops->preload()->triggerEvent('core.index.start');

//check if start page is defined
if ($xoops->isActiveModule($xoops->getConfig('startpage'))) {
    // Temporary solution for start page redirection
    define("XOOPS_STARTPAGE_REDIRECTED", 1);
    $module_handler = $xoops->getHandlerModule();
    $xoops->module = $xoops->getModuleByDirname($xoops->getConfig('startpage'));
    if (!$xoops->isModule() || !$xoops->module->getVar('isactive')) {
        $xoops->header();
        echo "<h4>" . XoopsLocale::E_NO_MODULE . "</h4>";
        $xoops->footer();
    }
    $moduleperm_handler = $xoops->getHandlerGroupperm();
    if ($xoops->isUser()) {
        if (!$moduleperm_handler->checkRight('module_read', $xoops->module->getVar('mid'), $xoops->user->getGroups())) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION, false);
        }
        $xoops->userIsAdmin = $xoops->user->isAdmin($xoops->module->getVar('mid'));
    } else {
        if (!$moduleperm_handler->checkRight('module_read', $xoops->module->getVar('mid'), XOOPS_GROUP_ANONYMOUS)) {
            $xoops->redirect(XOOPS_URL . "/user.php", 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }
    }
    if ($xoops->module->getVar('hasconfig') == 1
        || $xoops->module->getVar('hascomments') == 1
        || $xoops->module->getVar('hasnotification') == 1
    ) {
        $xoops->moduleConfig = $xoops->getModuleConfigs();
    }

    chdir('modules/' . $xoops->getConfig('startpage') . '/');
    $xoops->loadLanguage('main', $xoops->module->getVar('dirname', 'n'));
    $parsed = parse_url(XOOPS_URL);
    $url = isset($parsed['scheme']) ? $parsed['scheme'] . '://' : 'http://';
    if (isset($parsed['host'])) {
        $url .= $parsed['host'];
        if (isset($parsed['port'])) {
            $url .= ':' . $parsed['port'];
        }
    } else {
        $url .= $_SERVER['HTTP_HOST'];
    }

    $_SERVER['REQUEST_URI'] =
        substr(XOOPS_URL, strlen($url)) . '/modules/' . $xoops->getConfig('startpage') . '/index.php';
    include $xoops->path('modules/' . $xoops->getConfig('startpage') . '/index.php');
    exit();
} else {
    $xoops->setOption('show_cblock', 1);
    $xoops->header("module:system/system_homepage.tpl");
    $xoops->footer();
}

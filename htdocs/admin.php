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
 * XOOPS admin file
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package     core
 * @version     $Id$
 */

include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';

$xoops = Xoops::getInstance();
$xoops->isAdminSide = true;
include_once $xoops->path('include/cp_functions.php');

/**
 * Admin Authentication
 */
if ($xoops->isUser()) {
    if (!$xoops->user->isAdmin(-1)) {
        $xoops->redirect('index.php', 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
        exit();
    }
} else {
    $xoops->redirect('index.php', 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
    exit();
}

$xoops->header();
// ###### Output warn messages for security ######
/**
 * Error warning messages
 */
if ($xoops->getConfig('admin_warnings_enable')) {
    $error_msg = array();
    if (is_dir(XOOPS_ROOT_PATH . '/install/')) {
        $error_msg[] = sprintf(XoopsLocale::EF_DIRECTORY_EXISTS, XOOPS_ROOT_PATH . '/install/');
    }

    if (is_writable(XOOPS_ROOT_PATH . '/mainfile.php')) {
        $error_msg[] = sprintf(XoopsLocale::EF_FILE_IS_WRITABLE, XOOPS_ROOT_PATH . '/mainfile.php');
    }
    // ###### Output warn messages for correct functionality  ######
    if (!is_writable(XOOPS_CACHE_PATH)) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_NOT_WRITABLE, XOOPS_CACHE_PATH);
    }
    if (!is_writable(XOOPS_UPLOAD_PATH)) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_NOT_WRITABLE, XOOPS_UPLOAD_PATH);
    }
    if (!is_writable(XOOPS_COMPILE_PATH)) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_NOT_WRITABLE, XOOPS_COMPILE_PATH);
    }

    //www fits inside www_private, lets add a trailing slash to make sure it doesn't
    if (strpos(XOOPS_PATH . '/', XOOPS_ROOT_PATH . '/') !== false || strpos(XOOPS_PATH . '/', $_SERVER['DOCUMENT_ROOT'] . '/') !== false) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_IS_INSIDE_DOCUMENT_ROOT, XOOPS_PATH);
    }

    if (strpos(XOOPS_VAR_PATH . '/', XOOPS_ROOT_PATH . '/') !== false || strpos(XOOPS_VAR_PATH . '/', $_SERVER['DOCUMENT_ROOT'] . '/') !== false) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_IS_INSIDE_DOCUMENT_ROOT, XOOPS_VAR_PATH);
    }
    $xoops->tpl()->assign('error_msg', $error_msg);
}

if (!empty(Request::getString('xoopsorgnews', null, 'GET'))) {
    // Multiple feeds
    $myts = MyTextSanitizer::getInstance();
    $rssurl = array();
    $rssurl[] = 'http://sourceforge.net/export/rss2_projnews.php?group_id=41586&rss_fulltext=1';
    $rssurl[] = 'http://www.xoops.org/backend.php';
    $rssurl = array_unique(array_merge($rssurl, XoopsLocale::getAdminRssUrls()));
    $rssfile = 'adminnews-' . $xoops->getConfig('locale');

    $items = array();
    if (!$items = Xoops_Cache::read($rssfile)) {
        $snoopy = new Snoopy();
        $cnt = 0;
        foreach ($rssurl as $url) {
            if ($snoopy->fetch($url)) {
                $rssdata = $snoopy->results;
                $rss2parser = new XoopsXmlRss2Parser($rssdata);
                if (false != $rss2parser->parse()) {
                    $_items = $rss2parser->getItems();
                    $count = count($_items);
                    for ($i = 0; $i < $count; $i++) {
                        $_items[$i]['title'] = XoopsLocale::convert_encoding($_items[$i]['title'], XoopsLocale::getCharset(), 'UTF-8');
                        $_items[$i]['description'] = XoopsLocale::convert_encoding($_items[$i]['description'], _CHARSET, 'UTF-8');
                        $items[strval(strtotime($_items[$i]['pubdate'])) . "-" . strval(++$cnt)] = $_items[$i];
                    }
                } else {
                    echo $rss2parser->getErrors();
                }
            }
        }
        krsort($items);
        Xoops_Cache::write($rssfile, $items, 86400);
    }
    if ($items != '') {
        $ret = '<table class="outer width100">';
        foreach (array_keys($items) as $i) {
            $ret .= '<tr class="head"><td><a href="' . htmlspecialchars($items[$i]['link']) . '" rel="external">';
            $ret .= htmlspecialchars($items[$i]['title']) . '</a> (' . htmlspecialchars($items[$i]['pubdate']) . ')</td></tr>';
            if ($items[$i]['description'] != "") {
                $ret .= '<tr><td class="odd">' . $items[$i]['description'];
                if (!empty($items[$i]['guid'])) {
                    $ret .= '&nbsp;&nbsp;<a href="' . htmlspecialchars($items[$i]['guid']) . '" rel="external" title="">' . _MORE . '</a>';
                }
                $ret .= '</td></tr>';
            } else {
                if ($items[$i]['guid'] != "") {
                    $ret .= '<tr><td class="even aligntop"></td><td colspan="2" class="odd"><a href="' . htmlspecialchars($items[$i]['guid']) . '" rel="external">' . _MORE . '</a></td></tr>';
                }
            }
        }
        $ret .= '</table>';
        echo $ret;
    }
}
$xoops->footer();

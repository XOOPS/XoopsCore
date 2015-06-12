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
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package     core
 * @version     $Id$
 */

include __DIR__ . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->isAdminSide = true;
include_once $xoops->path('include/cp_functions.php');

$xbc = \XoopsBaseConfig::getInstance();

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

    $install_dir = $xoops->path('install');
    if (is_dir($install_dir)) {
        $error_msg[] = sprintf(XoopsLocale::EF_DIRECTORY_EXISTS, $install_dir);
    }

    $mainfile = $xoops->path('www/mainfile.php');
    if (is_writable($mainfile)) {
        $error_msg[] = sprintf(XoopsLocale::EF_FILE_IS_WRITABLE, $mainfile);
    }
    // ###### Output warn messages for correct functionality  ######
    $cache_path = $xoops->path('var/caches');
    if (!is_writable($cache_path)) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_NOT_WRITABLE, $cache_path);
    }
    $upload_path = $xoops->path('uploads');
    if (!is_writable($upload_path)) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_NOT_WRITABLE, $upload_path);
    }
    $compile_path = $xbc->get('smarty-compile');
    if (!is_writable($compile_path)) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_NOT_WRITABLE, $compile_path);
    }

    //www fits inside www_private, lets add a trailing slash to make sure it doesn't
    $xoops_path = $xbc->get('lib-path');
    $xoops_root_path = $xbc->get('root-path');
    if (strpos($xoops_path, $xoops_root_path) !== false || strpos($xoops_path, $_SERVER['DOCUMENT_ROOT']) !== false) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_IS_INSIDE_DOCUMENT_ROOT, $xoops_path);
    }

    $var_path = $xoops->path('var');
    if (strpos($var_path, $xoops_root_path) !== false || strpos($var_path, $_SERVER['DOCUMENT_ROOT']) !== false) {
        $error_msg[] = sprintf(XoopsLocale::EF_FOLDER_IS_INSIDE_DOCUMENT_ROOT, $var_path);
    }
    $xoops->tpl()->assign('error_msg', $error_msg);
}

$xoopsorgnews = Request::getString('xoopsorgnews', null, 'GET');
if (!empty($xoopsorgnews)) {
    // Multiple feeds
    $myts = MyTextSanitizer::getInstance();
    $rssurl = array();
    $rssurl[] = 'http://sourceforge.net/export/rss2_projnews.php?group_id=41586&rss_fulltext=1';
    $rssurl[] = 'http://www.xoops.org/backend.php';
    $rssurl = array_unique(array_merge($rssurl, XoopsLocale::getAdminRssUrls()));
    $rssfile = 'admin/rss/adminnews-' . $xoops->getConfig('locale');

    $items = $xoops->cache()->cacheRead($rssfile, 'buildRssFeedCache', 24*60*60, $rssurl);

    if ($items != '') {
        $ret = '<table class="outer width100">';
        foreach (array_keys($items) as $i) {
            $ret .= '<tr class="head"><td><a href="' . htmlspecialchars($items[$i]['link']) . '" rel="external">';
            $ret .= htmlspecialchars($items[$i]['title']) . '</a> (' . htmlspecialchars($items[$i]['pubdate']) . ')</td></tr>';
            if ($items[$i]['description'] != "") {
                $ret .= '<tr><td class="odd">' . $items[$i]['description'];
                if (!empty($items[$i]['guid'])) {
                    $ret .= '&nbsp;&nbsp;<a href="' . htmlspecialchars($items[$i]['guid']) . '" rel="external" title="">' . XoopsLocale::MORE . '</a>';
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

function buildRssFeedCache($rssurl)
{
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
                    $_items[$i]['description'] = XoopsLocale::convert_encoding($_items[$i]['description'], XoopsLocale::getCharset(), 'UTF-8');
                    $items[(string)(strtotime($_items[$i]['pubdate'])) . "-" . (string)(++$cnt)] = $_items[$i];
                }
            } else {
                echo $rss2parser->getErrors();
            }
        }
    }
    krsort($items);
    return $items;
}

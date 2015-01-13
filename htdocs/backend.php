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
 * XOOPS feed creator
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         core
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

if (file_exists('mainfile.php')) {
    include __DIR__ . DIRECTORY_SEPARATOR . 'mainfile.php';
} else {
    include '../../' . DIRECTORY_SEPARATOR . 'mainfile.php';
}
defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');

$dirname = $xoops->isModule() ? $xoops->module->getVar('dirname'): 'system';
$tpl = new XoopsTpl();
$tpl->caching = 2;
$tpl->cache_lifetime = 3600;
if (!$tpl->isCached('module:' . $dirname . '/system_rss.tpl')) {
    $tpl->assign('channel_title', XoopsLocale::convert_encoding(htmlspecialchars($xoops->getConfig('sitename'), ENT_QUOTES)));
    $tpl->assign('channel_link', XOOPS_URL . '/');
    $tpl->assign('channel_desc', XoopsLocale::convert_encoding(htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES)));
    $tpl->assign('channel_lastbuild', XoopsLocale::formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', $xoops->checkEmail($xoops->getConfig('adminmail'), true));
    $tpl->assign('channel_editor', $xoops->checkEmail($xoops->getConfig('adminmail'), true));
    $tpl->assign('channel_category', 'News');
    $tpl->assign('channel_generator', 'XOOPS');
    $tpl->assign('channel_language', XoopsLocale::getLangCode());

    $xoTheme = $xoops->theme();
    $imgPath = $xoTheme->resourcePath('/images/logo.png');
    $tpl->assign('image_url', $xoops->url($imgPath));
    $dimension = getimagesize($xoops->path($imgPath));
    $tpl->assign('image_width', $dimension[0]);
    $tpl->assign('image_height', $dimension[1]);

    $items = array();

    if ($xoops->isModule()) {
        /* @var $plugin SystemPluginInterface */
        $plugin = Xoops_Module_Plugin::getPlugin($dirname, 'system');
        $res = $plugin->backend(10);
        if (is_array($res) && count($res)>0) {
            foreach ($res as $item) {
                $date[] = array('date' => $item['date']);
                $items[] = array('date' => XoopsLocale::formatTimestamp($item['date'], 'rss'),
                                 'title' => XoopsLocale::convert_encoding(htmlspecialchars($item['title'])),
                                 'content' => XoopsLocale::convert_encoding(htmlspecialchars($item['content'])),
                                 'link' => $item['link'],
                                 'guid' => $item['link'],
                                 );
            }
        }
    } else {
        $plugins = Xoops_Module_Plugin::getPlugins('system');
        /* @var $plugin SystemPluginInterface */
        foreach ($plugins as $plugin) {
            $res = $plugin->backend(10);
            if (is_array($res) && count($res)>0) {
                foreach ($res as $item) {
                    $date[] = array('date' => $item['date']);
                    $items[] = array('date' => XoopsLocale::formatTimestamp($item['date'], 'rss'),
                                     'title' => XoopsLocale::convert_encoding(htmlspecialchars($item['title'])),
                                     'content' => XoopsLocale::convert_encoding(htmlspecialchars($item['content'])),
                                     'link' => $item['link'],
                                     'guid' => $item['link'],
                                     );
                }
            }
        }
    }
    array_multisort($date, SORT_DESC, $items);
    $tpl->assign('items', $items);

}
$tpl->display('module:' . $dirname . '/system_rss.tpl');

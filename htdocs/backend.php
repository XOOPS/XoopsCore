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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         core
 * @since           2.6.0
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */


require __DIR__ . '/mainfile.php';

$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

$myts = \Xoops\Core\Text\Sanitizer::getInstance();

$xoops_url = \XoopsBaseConfig::get('url');

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');
$dirname = $xoops->isModule() ? $xoops->module->getVar('dirname'): 'system';
$tpl = new XoopsTpl();
$tpl->caching = 2;
$tpl->cache_lifetime = 3600;
if (!$tpl->isCached('module:' . $dirname . '/system_rss.tpl')) {
    $tpl->assign('channel_title', $myts->htmlspecialchars($xoops->getConfig('sitename')));
    $tpl->assign('channel_link', $xoops_url . '/');
    $tpl->assign('channel_desc', $myts->htmlspecialchars($xoops->getConfig('slogan')));
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
        $plugin = Xoops\Module\Plugin::getPlugin($dirname, 'system');
        $res = $plugin->backend(10);
        if (is_array($res) && count($res)>0) {
            foreach ($res as $item) {
                $date[] = array('date' => $item['date']);
                $items[] = array('date' => XoopsLocale::formatTimestamp($item['date'], 'rss'),
                                 'title' => $myts->htmlspecialchars($item['title']),
                                 'content' => $myts->htmlspecialchars($item['content']),
                                 'link' => $item['link'],
                                 'guid' => $item['link'],
                                 );
            }
        }
    } else {
        $plugins = Xoops\Module\Plugin::getPlugins('system');
        /* @var $plugin SystemPluginInterface */
        foreach ($plugins as $plugin) {
            $res = $plugin->backend(10);
            if (is_array($res) && count($res)>0) {
                foreach ($res as $item) {
                    $date[] = array('date' => $item['date']);
                    $items[] = array('date' => XoopsLocale::formatTimestamp($item['date'], 'rss'),
                                     'title' => $myts->htmlspecialchars($item['title']),
                                     'content' => $myts->htmlspecialchars($item['content']),
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

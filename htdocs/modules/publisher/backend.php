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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @subpackage      Action
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';
$xoops = Xoops::getInstance();
$xoops->disableErrorReporting();

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}

$categoryid = isset($_GET['categoryid']) ? $_GET['categoryid'] : -1;

if ($categoryid != -1) {
    /* @var $categoryObj PublisherCategory */
    $categoryObj = $publisher->getCategoryHandler()->get($categoryid);
}

header('Content-Type:text/xml; charset=' . XoopsLocale::getCharset());
$tpl = new XoopsTpl();
$tpl->caching = 2;
$tpl->cache_lifetime= 0;

$myts = MyTextSanitizer::getInstance();
if (!$tpl->isCached('module:publisher/publisher_rss.tpl')) {
    $channel_category = $publisher->getModule()->getVar('name');
    $tpl->assign('channel_charset', XoopsLocale::getCharset());
    $tpl->assign('channel_title', htmlspecialchars($xoops->getConfig('sitename'), ENT_QUOTES));
    $tpl->assign('channel_link', PUBLISHER_URL);
    $tpl->assign('channel_desc', htmlspecialchars($xoops->getConfig('slogan'), ENT_QUOTES));
    $tpl->assign('channel_lastbuild', XoopsLocale::formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', $xoops->getConfig('adminmail'));
    $tpl->assign('channel_editor', $xoops->getConfig('adminmail'));

    if ($categoryid != -1) {
        $channel_category .= " > " . $categoryObj->getVar('name');
    }

    $tpl->assign('channel_category', htmlspecialchars($channel_category));
    $tpl->assign('channel_generator', $publisher->getModule()->getVar('name'));
    $tpl->assign('channel_language', XoopsLocale::getLangCode());
    $tpl->assign('image_url', \XoopsBaseConfig::get('url') . '/images/logo.gif');
    $dimention = getimagesize(\XoopsBaseConfig::get('root-path') . '/images/logo.gif');
    if (empty($dimention[0])) {
        $width = 140;
        $height = 140;
    } else {
        $width = ($dimention[0] > 140) ? 140 : $dimention[0];
        $dimention[1] = $dimention[1] * $width / $dimention[0];
        $height = ($dimention[1] > 140) ? $dimention[1] * $dimention[0] / 140 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);
    $sarray = $publisher->getItemHandler()->getAllPublished(10, 0, $categoryid);
    if (is_array($sarray)) {
        $count = $sarray;
        /* @var $item PublisherItem */
        foreach ($sarray as $item) {
            $tpl->append('items',
                         array('title' => htmlspecialchars($item->title(), ENT_QUOTES),
                               'link' => $item->getItemUrl(),
                               'guid' => $item->getItemUrl(),
                               'pubdate' => XoopsLocale::formatTimestamp($item->getVar('datesub'), 'rss'),
                               'description' => htmlspecialchars($item->getBlockSummary(300, true), ENT_QUOTES)));
        }
    }
}
$tpl->display('module:publisher/publisher_rss.tpl');

<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xmf\Request;
use Xoops\Core\Text\Sanitizer;
use XoopsModules\Publisher;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
require_once __DIR__ . '/header.php';

$xoops = Xoops::getInstance();
$uid = Request::getInt('uid');
if (!$uid) {
    $xoops->redirect('index.php', 2, _CO_PUBLISHER_ERROR);
}

$memberHandler = $xoops->getHandlerMember();
$thisuser = $memberHandler->getUser($uid);
if (!is_object($thisuser)) {
    $xoops->redirect('index.php', 2, _CO_PUBLISHER_ERROR);
}

if (!$helper->getConfig('perm_author_items')) {
    $xoops->redirect('index.php', 2, _CO_PUBLISHER_ERROR);
}

$myts = Sanitizer::getInstance();

$xoops->header('publisher_author_items.tpl');
$xoopsTpl = $xoops->tpl();

XoopsLoad::loadFile($helper->path('footer.php'));

$criteria = new CriteriaCompo(new Criteria('datesub', time(), '<='));
$criteria->add(new Criteria('uid', $uid));

$items = $helper->getItemHandler()->getItems($limit = 0, $start = 0, [_PUBLISHER_STATUS_PUBLISHED], -1, 'datesub', 'DESC', '', true, $criteria);
unset($criteria);
$count = count($items);

$xoopsTpl->assign('total_items', $count);
$xoopsTpl->assign('rating', $helper->getConfig('perm_rating'));

$author_name = XoopsUserUtility::getUnameFromId($uid, $helper->getConfig('format_realname'), true);
$xoopsTpl->assign('author_name_with_link', $author_name);

$xoopsTpl->assign('user_avatarurl', $xoops->service('avatar')->getAvatarUrl($uid)->getValue());
$categories = [];
if ($count > 0) {
    /* @var Publisher\Item $item */
    foreach ($items as $item) {
        $catid = $item->getVar('categoryid');
        if (!isset($categories[$catid])) {
            $categories[$catid] = [
                'count_items' => 0,
                'count_hits' => 0,
                'title' => $item->getCategoryName(),
                'link' => $item->getCategoryLink(),
            ];
        }

        $categories[$catid]['count_items']++;
        $categories[$catid]['count_hits'] += $item->getVar('counter');
        $categories[$catid]['items'][] = [
            'title' => $item->title(),
            'hits' => $item->getVar('counter'),
            'link' => $item->getItemLink(),
            'published' => $item->datesub(),
            'rating' => $item->getVar('rating'),
        ];
    }
}

$xoopsTpl->assign('categories', $categories);

$title = _MD_PUBLISHER_ITEMS_SAME_AUTHOR . ' - ' . $author_name;

/**
 * Generating meta information for this page
 */
$publisher_metagen = new Publisher\Metagen($title, '', $title);
$publisher_metagen->createMetaTags();
$xoops->footer();

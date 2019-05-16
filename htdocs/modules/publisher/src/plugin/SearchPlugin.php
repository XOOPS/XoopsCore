<?php

namespace XoopsModules\Publisher\Plugin;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use SearchPluginInterface;
use Xmf\Metagen;
use Xoops\Module\Plugin\PluginAbstract;
use XoopsModules\Publisher;
use XoopsModules\Publisher\Helper;
use XoopsUserUtility;

/**
 *  Publisher class
 *
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Class
 * @subpackage      Utils
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class SearchPlugin extends PluginAbstract implements SearchPluginInterface
{
    /**
     * @param string[] $queryarray
     * @param string   $andor
     * @param int      $limit
     * @param int      $offset
     * @param int      $userid
     * @return array
     */
    public function search($queryarray, $andor, $limit, $offset, $userid)
    {
        $categories = [];
        $sortby = 0;
        $searchin = '';
        $extra = '';

        return self::searchAdvanced($queryarray, $andor, $limit, $offset, $userid, $categories, $sortby, $searchin, $extra);
    }

    /**
     * @param        $queryarray
     * @param        $andor
     * @param        $limit
     * @param        $offset
     * @param        $userid
     * @param array  $categories
     * @param int    $sortby
     * @param string $searchin
     * @param string $extra
     */
    public static function searchAdvanced($queryarray, $andor, $limit, $offset, $userid, $categories = [], $sortby = 0, $searchin = '', $extra = ''): array
    {
        $helper = Helper::getInstance();
        $ret = [];
        if ('' == $queryarray || 0 == \count($queryarray)) {
            $hightlight_key = '';
        } else {
            $keywords = \implode('+', $queryarray);
            $hightlight_key = '&amp;keywords=' . $keywords;
        }
        $itemsObjs = $helper->getItemHandler()->getItemsFromSearch($queryarray, $andor, $limit, $offset, $userid, $categories, $sortby, $searchin, $extra);
        $withCategoryPath = $helper->getConfig('search_cat_path');

        $usersIds = [];
        /* @var Publisher\Item $obj */
        foreach ($itemsObjs as $obj) {
            $item['image'] = 'images/item_icon.gif';
            $item['link'] = $obj->getItemUrl();
            $item['link'] .= (!empty($hightlight_key) && (false === \mb_strpos($item['link'], '.php?'))) ? '?' . \ltrim($hightlight_key, '&amp;') : $hightlight_key;
            if ($withCategoryPath) {
                $item['title'] = $obj->getCategoryPath(false) . ' > ' . $obj->title();
            } else {
                $item['title'] = $obj->title();
            }
            $item['time'] = $obj->getVar('datesub'); //must go has unix timestamp
            $item['uid'] = $obj->getVar('uid');
            $item['content'] = Metagen::getSearchSummary($obj->body(), $queryarray);
            $item['author'] = $obj->getVar('author_alias');
            $item['datesub'] = $obj->datesub($helper->getConfig('format_date'));
            $usersIds[$obj->getVar('uid')] = $obj->getVar('uid');
            $ret[] = $item;
            unset($item, $sanitized_text);
        }
        $usersNames = XoopsUserUtility::getUnameFromIds($usersIds, $helper->getConfig('format_realname'), true);
        foreach ($ret as $key => $item) {
            if ('' == $item['author']) {
                $ret[$key]['author'] = @$usersNames[$item['uid']];
            }
        }
        unset($usersNames, $usersIds);

        return $ret;
    }
}

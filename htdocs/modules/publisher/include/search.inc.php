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
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

function publisher_search($queryarray, $andor, $limit, $offset, $userid, $categories = array(), $sortby = 0, $searchin = "", $extra = "")
{
    $publisher = Publisher::getInstance();
    $ret = array();
    if ($queryarray == '' || count($queryarray) == 0) {
        $hightlight_key = '';
    } else {
        $keywords = implode('+', $queryarray);
        $hightlight_key = "&amp;keywords=" . $keywords;
    }
    $itemsObjs = $publisher->getItemHandler()
            ->getItemsFromSearch($queryarray, $andor, $limit, $offset, $userid, $categories, $sortby, $searchin, $extra);
    $withCategoryPath = $publisher->getConfig('search_cat_path');

    $usersIds = array();
    /* @var $obj PublisherItem */
    foreach ($itemsObjs as $obj) {
        $item['image'] = "images/item_icon.gif";
        $item['link'] = $obj->getItemUrl();
        $item['link'] .= (!empty($hightlight_key) && (strpos($item['link'], '.php?') === false)) ? "?" . ltrim($hightlight_key, '&amp;') : $hightlight_key;
        if ($withCategoryPath) {
            $item['title'] = $obj->getCategoryPath(false) . " > " . $obj->title();
        } else {
            $item['title'] = $obj->title();
        }
        $item['time'] = $obj->getVar('datesub'); //must go has unix timestamp
        $item['uid'] = $obj->getVar('uid');
        //"Fulltext search/highlight
        $text = $obj->body();
        $sanitized_text = "";
        $text_i = strtolower($text);
        $queryarray = is_array($queryarray) ? $queryarray : array($queryarray);

        //@todo look into xoopslocal
        foreach ($queryarray as $query) {
            $pos = strpos($text_i, strtolower($query)); //xoops_local("strpos", $text_i, strtolower($query));
            $start = max(($pos - 100), 0);
            $length = strlen($query) + 200; //xoops_local("strlen", $query) + 200;
            $context = $obj->highlight(XoopsLocale::substr($text, $start, $length, " [...]"), $query);
            $sanitized_text .= "<p>[...] " . $context . "</p>";
        }

        //End of highlight
        $item['text'] = $sanitized_text;
        $item['author'] = $obj->getVar('author_alias');
        $item['datesub'] = $obj->datesub($publisher->getConfig('format_date'));
        $usersIds[$obj->getVar('uid')] = $obj->getVar('uid');
        $ret[] = $item;
        unset($item, $sanitized_text);
    }
    $usersNames = XoopsUserUtility::getUnameFromIds($usersIds, $publisher->getConfig('format_realname'), true);
    foreach ($ret as $key => $item) {
        if ($item["author"] == '') {
            $ret[$key]["author"] = @$usersNames[$item["uid"]];
        }
    }
    unset($usersNames, $usersIds);
    return $ret;
}

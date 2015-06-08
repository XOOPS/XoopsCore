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
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @subpackage      Action
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';
$xoops = Xoops::getInstance();

$publisher = Publisher::getInstance();
$myts = MyTextSanitizer::getInstance();

// @todo no such config is set, should it be? Or should only the system search plugin be used?
//Checking general permissions
$xoopsConfigSearch = $xoops->getConfigs();
if (empty($xoopsConfigSearch["enable_search"])) {
    $xoops->redirect(PUBLISHER_URL . "/index.php", 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$groups = $xoops->getUserGroups();
$gperm_handler = $publisher->getGrouppermHandler();
$module_id = $publisher->getModule()->mid();

//Checking permissions
if (!$publisher->getConfig('perm_search')
    || !$gperm_handler->checkRight('global', _PUBLISHER_SEARCH, $groups, $module_id)
) {
    $xoops->redirect(PUBLISHER_URL, 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
}

$xoops->disableModuleCache();
$xoops->header('module:publisher/publisher_search.tpl');
$xoopsTpl = $xoops->tpl();

$module_info_search = $publisher->getModule()->getInfo("search");
XoopsLoad::loadFile($publisher->path($module_info_search["file"]));

$limit = 10; //$publisher->getConfig('idxcat_perpage');
$uid = 0;
$queries = array();
$andor = Request::getString('andor');
$start = Request::getInt('start');
$category = Request::getArray('category');
$username = Request::getString('uname');
$searchin = Request::getArray('searchin');
$sortby = Request::getString('sortby');
$term = Request::getString('term');

if (empty($category) || (is_array($category) && in_array("all", $category))) {
    $category = array();
} else {
    $category = !is_array($category) ? explode(",", $category) : $category;
    $category = array_map("intval", $category);
}

$andor = (in_array(strtoupper($andor), array("OR", "AND", "EXACT"))) ? strtoupper($andor) : "OR";
$sortby = (in_array(strtolower($sortby), array("itemid", "datesub", "title", "categoryid")))
            ? strtolower($sortby) : "itemid";

if (!(empty($_POST["submit"]) && empty($term))) {

    $next_search["category"] = implode(",", $category);
    $next_search["andor"] = $andor;
    $next_search["term"] = $term;
    $query = trim($term);

    if ($andor != "EXACT") {
        $ignored_queries = array(); // holds kewords that are shorter than allowed minmum length
        $temp_queries = preg_split("/[\s,]+/", $query);
        foreach ($temp_queries as $q) {
            $q = trim($q);
            if (strlen($q) >= $xoopsConfigSearch["keyword_min"]) {
                $queries[] = $myts->addSlashes($q);
            } else {
                $ignored_queries[] = $myts->addSlashes($q);
            }
        }
        if (count($queries) == 0) {
            $xoops->redirect(
                PUBLISHER_URL . "/search.php",
                2,
                sprintf(XoopsLocale::EF_KEYWORDS_MUST_BE_GREATER_THAN, $xoopsConfigSearch["keyword_min"])
            );
            exit();
        }
    } else {
        if (strlen($query) < $xoopsConfigSearch["keyword_min"]) {
            $xoops->redirect(
                PUBLISHER_URL . "/search.php",
                2,
                sprintf(XoopsLocale::EF_KEYWORDS_MUST_BE_GREATER_THAN, $xoopsConfigSearch["keyword_min"])
            );
            exit();
        }
        $queries = array($myts->addSlashes($query));
    }

    $uname_required = false;
    $search_username = trim($username);
    $next_search["uname"] = $search_username;
    if (!empty($search_username)) {
        $uname_required = true;
        $search_username = $myts->addSlashes($search_username);
        if (!$result = $xoopsDB->query(
            "SELECT uid FROM " . $xoopsDB->prefix("users") .
            " WHERE uname LIKE " . $xoopsDB->quoteString("%$search_username%")
        )) {
            $xoops->redirect(PUBLISHER_URL . "/search.php", 1, _CO_PUBLISHER_ERROR);
            exit();
        }
        $uid = array();
        while ($row = $xoopsDB->fetchArray($result)) {
            $uid[] = $row["uid"];
        }
    } else {
        $uid = 0;
    }

    $next_search["sortby"] = $sortby;
    $next_search["searchin"] = implode("|", $searchin);

    if (!empty($time)) {
        $extra = "";
    } else {
        $extra = "";
    }

    if ($uname_required && (!$uid || count($uid) < 1)) {
        $results = array();
    } else {
        $results =
            $module_info_search["func"]($queries, $andor, $limit, $start, $uid, $category, $sortby, $searchin, $extra);
    }

    if (count($results) < 1) {
        $results[] = array("text" => XoopsLocale::NO_MATCH_FOUND_FOR_QUERY);
    }

    $xoopsTpl->assign("results", $results);
    $paras = '';
    if (count($next_search) > 0) {
        $items = array();
        foreach ($next_search as $para => $val) {
            if (!empty($val)) {
                $items[] = "{$para}={$val}";
            }
        }
        if (count($items) > 0) {
            $paras = implode("&", $items);
        }
        unset($next_search);
        unset($items);
    }
    $search_url = $publisher->url("search.php?" . $paras);

    if (count($results)) {
        $next = $start + $limit;
        $queries = implode(",", $queries);
        $search_url_next = $search_url . "&start={$next}";
        $search_next = "<a href=\"" . htmlspecialchars($search_url_next) . "\">" . XoopsLocale::NEXT . "</a>";
        $xoopsTpl->assign("search_next", $search_next);
    }
    if ($start > 0) {
        $prev = $start - $limit;
        $search_url_prev = $search_url . "&start={$prev}";
        $search_prev = "<a href=\"" . htmlspecialchars($search_url_prev) . "\">" . XoopsLocale::PREVIOUS . "</a>";
        $xoopsTpl->assign("search_prev", $search_prev);
    }

    unset($results);
    $search_info = XoopsLocale::KEYWORDS . ": " . $myts->htmlSpecialChars($term);
    if ($uname_required) {
        if ($search_info) {
            $search_info .= "<br />";
        }
        $search_info .= _CO_PUBLISHER_UID . ": " . $myts->htmlSpecialChars($search_username);
    }
    $xoopsTpl->assign("search_info", $search_info);
}

/* type */
$type_select = "<select name=\"andor\">";
$type_select .= "<option value=\"OR\"";
if ("OR" == $andor) {
    $type_select .= " selected=\"selected\"";
}
$type_select .= ">" . XoopsLocale::ANY_OR . "</option>";
$type_select .= "<option value=\"AND\"";
if ("AND" == $andor) {
    $type_select .= " selected=\"selected\"";
}
$type_select .= ">" . XoopsLocale::ALL . "</option>";
$type_select .= "<option value=\"EXACT\"";
if ("EXACT" == $andor) {
    $type_select .= " selected=\"selected\"";
}
$type_select .= ">" . XoopsLocale::EXACT_MATCH . "</option>";
$type_select .= "</select>";

/* category */
$categories = $publisher->getCategoryHandler()->getCategoriesForSearch();

$select_category = "<select name=\"category[]\" size=\"5\" multiple=\"multiple\">";
$select_category .= "<option value=\"all\"";
if (empty($category) || count($category) == 0) {
    $select_category .= "selected=\"selected\"";
}
$select_category .= ">" . XoopsLocale::ALL . "</option>";
foreach ($categories as $id => $cat) {
    $select_category .= "<option value=\"" . $id . "\"";
    if (in_array($id, $category)) {
        $select_category .= "selected=\"selected\"";
    }
    $select_category .= ">" . $cat . "</option>";
}
$select_category .= "</select>";

/* scope */
$searchin_select = "";
$searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"title\"";
if (in_array("title", $searchin)) {
    $searchin_select .= " checked";
}
$searchin_select .= " />" . _CO_PUBLISHER_TITLE . "&nbsp;&nbsp;";
$searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"subtitle\"";
if (in_array("subtitle", $searchin)) {
    $searchin_select .= " checked";
}
$searchin_select .= " />" . _CO_PUBLISHER_SUBTITLE . "&nbsp;&nbsp;";
$searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"summary\"";
if (in_array("summary", $searchin)) {
    $searchin_select .= " checked";
}
$searchin_select .= " />" . _CO_PUBLISHER_SUMMARY . "&nbsp;&nbsp;";
$searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"text\"";
if (in_array("body", $searchin)) {
    $searchin_select .= " checked";
}
$searchin_select .= " />" . _CO_PUBLISHER_BODY . "&nbsp;&nbsp;";
$searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"keywords\"";
if (in_array("meta_keywords", $searchin)) {
    $searchin_select .= " checked";
}
$searchin_select .= " />" . _CO_PUBLISHER_ITEM_META_KEYWORDS . "&nbsp;&nbsp;";
$searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"all\"";
if (in_array("all", $searchin) || empty($searchin)) {
    $searchin_select .= " checked";
}
$searchin_select .= " />" . XoopsLocale::ALL . "&nbsp;&nbsp;";

/* sortby */
$sortby_select = "<select name=\"sortby\">";
$sortby_select .= "<option value=\"itemid\"";
if ("itemid" == $sortby || empty($sortby)) {
    $sortby_select .= " selected=\"selected\"";
}
$sortby_select .= ">" . XoopsLocale::NONE . "</option>";
$sortby_select .= "<option value=\"datesub\"";
if ("datesub" == $sortby) {
    $sortby_select .= " selected=\"selected\"";
}
$sortby_select .= ">" . _CO_PUBLISHER_DATESUB . "</option>";
$sortby_select .= "<option value=\"title\"";
if ("title" == $sortby) {
    $sortby_select .= " selected=\"selected\"";
}
$sortby_select .= ">" . _CO_PUBLISHER_TITLE . "</option>";
$sortby_select .= "<option value=\"categoryid\"";
if ("categoryid" == $sortby) {
    $sortby_select .= " selected=\"selected\"";
}
$sortby_select .= ">" . _CO_PUBLISHER_CATEGORY . "</option>";
$sortby_select .= "</select>";

$xoopsTpl->assign("type_select", $type_select);
$xoopsTpl->assign("searchin_select", $searchin_select);
$xoopsTpl->assign("category_select", $select_category);
$xoopsTpl->assign("sortby_select", $sortby_select);
$xoopsTpl->assign("search_term", $term);
$xoopsTpl->assign("search_user", $username);

$xoopsTpl->assign("modulename", $publisher->getModule()->getVar('name'));

if ($xoopsConfigSearch["keyword_min"] > 0) {
    $xoopsTpl->assign(
        "search_rule",
        sprintf(XoopsLocale::F_KEYWORDS_SHORTER_THAN_WILL_BE_IGNORED, $xoopsConfigSearch["keyword_min"])
    );
}

$xoops->footer();

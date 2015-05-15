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
 * @subpackage      Blocks
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          phppp
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

function publisher_search_show($options)
{
    $block = array();
    $xoops = Xoops::getInstance();
    $publisher = Publisher::getInstance();
    $categories = $publisher->getCategoryHandler()->getCategoriesForSearch();
    if (count($categories) == 0) return $block;

    $andor = isset($_POST["andor"]) ? $_POST["andor"] : (isset($_GET["andor"]) ? $_GET["andor"] : "");

    $category = isset($_POST["category"]) ? $_POST["category"] : (isset($_GET["category"]) ? $_GET["category"] : null);
    $username = isset($_POST["uname"]) ? $_POST["uname"] : (isset($_GET["uname"]) ? $_GET["uname"] : null);
    $searchin = isset($_POST["searchin"]) ? $_POST["searchin"] : (isset($_GET["searchin"]) ? explode("|", $_GET["searchin"]) : array());
    $sortby = isset($_POST["sortby"]) ? $_POST["sortby"] : (isset($_GET["sortby"]) ? $_GET["sortby"] : null);
    $term = isset($_POST["term"]) ? $_POST["term"] : (isset($_GET["term"]) ? $_GET["term"] : "");

    if (empty($category) || (is_array($category) && in_array("all", $category))) {
        $category = array();
    } else {
        $category = (!is_array($category)) ? explode(",", $category) : $category;
        $category = array_map("intval", $category);
    }

    $andor = (in_array(strtoupper($andor), array("OR", "AND", "EXACT"))) ? strtoupper($andor) : "OR";
    $sortby = (in_array(strtolower($sortby), array("itemid", "datesub", "title", "categoryid"))) ? strtolower($sortby) : "itemid";

    /* type */
    $type_select = "<select name=\"andor\">";
    $type_select .= "<option value=\"OR\"";
    if ("OR" == $andor) $type_select .= " selected=\"selected\"";
    $type_select .= ">" . XoopsLocale::ANY_OR . "</option>";
    $type_select .= "<option value=\"AND\"";
    if ("AND" == $andor) $type_select .= " selected=\"selected\"";
    $type_select .= ">" . XoopsLocale::ALL . "</option>";
    $type_select .= "<option value=\"EXACT\"";
    if ("exact" == $andor) $type_select .= " selected=\"selected\"";
    $type_select .= ">" . XoopsLocale::EXACT_MATCH . "</option>";
    $type_select .= "</select>";

    /* category */

    $select_category = "<select name=\"category[]\" size=\"5\" multiple=\"multiple\" width=\"150\" style=\"width:150px;\">";
    $select_category .= "<option value=\"all\"";
    if (empty($category) || count($category) == 0) $select_category .= "selected=\"selected\"";
    $select_category .= ">" . XoopsLocale::ALL . "</option>";
    foreach ($categories as $id => $cat) {
        $select_category .= "<option value=\"" . $id . "\"";
        if (in_array($id, $category)) $select_category .= "selected=\"selected\"";
        $select_category .= ">" . $cat . "</option>";
    }
    $select_category .= "</select>";

    /* scope */
    $searchin_select = "";
    $searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"title\"";
    if (in_array("title", $searchin)) $searchin_select .= " checked";
    $searchin_select .= " />" . _CO_PUBLISHER_TITLE . "&nbsp;&nbsp;";
    $searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"subtitle\"";
    if (in_array("subtitle", $searchin)) $searchin_select .= " checked";
    $searchin_select .= " />" . _CO_PUBLISHER_SUBTITLE . "&nbsp;&nbsp;";
    $searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"summary\"";
    if (in_array("summary", $searchin)) $searchin_select .= " checked";
    $searchin_select .= " />" . _CO_PUBLISHER_SUMMARY . "&nbsp;&nbsp;";
    $searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"text\"";
    if (in_array("body", $searchin)) $searchin_select .= " checked";
    $searchin_select .= " />" . _CO_PUBLISHER_BODY . "&nbsp;&nbsp;";
    $searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"keywords\"";
    if (in_array("meta_keywords", $searchin)) $searchin_select .= " checked";
    $searchin_select .= " />" . _CO_PUBLISHER_ITEM_META_KEYWORDS . "&nbsp;&nbsp;";
    $searchin_select .= "<input type=\"checkbox\" name=\"searchin[]\" value=\"all\"";
    if (in_array("all", $searchin) || empty($searchin)) $searchin_select .= " checked";
    $searchin_select .= " />" . XoopsLocale::ALL . "&nbsp;&nbsp;";

    /* sortby */
    $sortby_select = "<select name=\"sortby\">";
    $sortby_select .= "<option value=\"itemid\"";
    if ("itemid" == $sortby || empty($sortby)) $sortby_select .= " selected=\"selected\"";
    $sortby_select .= ">" . XoopsLocale::NONE . "</option>";
    $sortby_select .= "<option value=\"datesub\"";
    if ("datesub" == $sortby) $sortby_select .= " selected=\"selected\"";
    $sortby_select .= ">" . _CO_PUBLISHER_DATESUB . "</option>";
    $sortby_select .= "<option value=\"title\"";
    if ("title" == $sortby) $sortby_select .= " selected=\"selected\"";
    $sortby_select .= ">" . _CO_PUBLISHER_TITLE . "</option>";
    $sortby_select .= "<option value=\"categoryid\"";
    if ("categoryid" == $sortby) $sortby_select .= " selected=\"selected\"";
    $sortby_select .= ">" . _CO_PUBLISHER_CATEGORY . "</option>";
    $sortby_select .= "</select>";

    $block["type_select"] = $type_select;
    $block["searchin_select"] = $searchin_select;
    $block["category_select"] = $select_category;
    $block["sortby_select"] = $sortby_select;
    $block["search_term"] = $term;
    $block["search_user"] = $username;
    $block["publisher_url"] = PUBLISHER_URL;

    return $block;
}
